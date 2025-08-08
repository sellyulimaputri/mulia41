<?php

namespace app\models\sales;

use app\models\master\BusinessPartner;
use Yii;
use app\models\master\MasterItem;
use app\models\master\MasterCustomer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use app\models\master\MasterRawMaterial;
use app\models\master\MasterTypeProduksi;
use app\models\sales\SalesOrderStandardDetail;

/**
 * This is the model class for table "sales_order_standard".
 *
 * @property int $id
 * @property string $no_so
 * @property string $tanggal
 * @property int $id_customer
 * @property string $deliver_date
 *
 * @property MasterCustomer $customer
 * @property SalesOrderStandardDetail[] $salesOrderStandardDetails
 */
class SalesOrderStandard extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_order_standard';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_so', 'tanggal', 'id_customer', 'deliver_date'], 'required'],
            [['tanggal', 'deliver_date'], 'safe'],
            [['id_customer'], 'integer'],
            [['no_so'], 'string', 'max' => 255],
            [['id_customer'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessPartner::class, 'targetAttribute' => ['id_customer' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_so' => 'No Sales Order',
            'tanggal' => 'Date',
            'id_customer' => 'Customer',
            'deliver_date' => 'Delivery Date',
        ];
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(BusinessPartner::class, ['id' => 'id_customer']);
    }

    /**
     * Gets query for [[SalesOrderStandardDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSalesOrderStandardDetails()
    {
        return $this->hasMany(SalesOrderStandardDetail::class, ['id_header' => 'id']);
    }

    public function importExcelToDetails($excelFile)
    {
        try {
            $spreadsheet = IOFactory::load($excelFile->tempName);

            foreach ($spreadsheet->getSheetNames() as $sheetName) {
                if (stripos($sheetName, 'HPL') === false) {
                    continue;
                }

                $sheet = $spreadsheet->getSheetByName($sheetName);
                $rows = $sheet->toArray();

                foreach ($rows as $i => $row) {
                    if ($i < 2) continue;

                    $articleCode = strtolower(trim((string) $row[1]));
                    $colour = strtolower(trim((string) $row[7]));
                    $ralCode = strtolower(trim((string) $row[8]));

                    $item = MasterItem::find()
                        ->where(['LIKE', 'LOWER(item_name)', $articleCode, false])
                        ->one();

                    // Penentuan type_produksi langsung dari keyword colour
                    $typeProduksi = null;
                    if (strpos($colour, 'galva') !== false) {
                        $typeProduksi = 1;
                    } elseif (strpos($colour, 'pc') !== false) {
                        $typeProduksi = 2;
                    }

                    $rawMaterial = (!empty($ralCode) && $ralCode !== 'null') ?
                        MasterRawMaterial::find()->where(['LIKE', 'LOWER(item_code)', $ralCode, false])->one() : null;

                    if (!$item || !$typeProduksi) {
                        Yii::$app->session->addFlash('error', "Baris dilewati - Sheet: $sheetName, Baris $i: item atau type produksi tidak ditemukan.");
                        continue;
                    }

                    $detail = new SalesOrderStandardDetail([
                        'id_item' => $item->id,
                        'type_produksi' => $typeProduksi,
                        'id_raw_material' => $rawMaterial ? $rawMaterial->id : null,
                        'description' => (string) $row[13],
                        'qty' => $this->normalizeNumber($row[0]),
                        'length' => $this->normalizeNumber($row[2]),
                        'total' => $this->normalizeNumber($row[12]),
                        'harga' => $this->normalizeNumber($row[10]),
                        'id_header' => $this->id,
                    ]);

                    if (!$detail->save()) {
                        return "Sheet: $sheetName, Baris $i gagal simpan: " . json_encode($detail->errors);
                    }
                }
            }

            return true;
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    private function normalizeNumber($value)
    {
        if (is_string($value)) {
            // Hilangkan koma (pemisah ribuan), titik tetap (sebagai desimal)
            $value = str_replace(',', '', $value);
        }

        return (float) $value;
    }

    public static function getDropdownList()
    {
        return \yii\helpers\ArrayHelper::map(
            self::find()->orderBy(['tanggal' => SORT_DESC])->all(),
            'id',
            function ($model) {
                return $model->no_so;
            }
        );
    }
}