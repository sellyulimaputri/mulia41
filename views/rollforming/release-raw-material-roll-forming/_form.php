<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\SearchableSelect;

/** @var yii\web\View $this */
/** @var app\models\rollforming\ReleaseRawMaterialRollForming $model */
/** @var yii\widgets\ActiveForm $form */
?>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<div class="release-raw-material-roll-forming-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'no_release')->textInput(['maxlength' => true, 'placeholder' => 'Enter No Release']) ?>
        </div>

        <div class="col-md-2">
            <label class="control-label">Nomor SO</label>
            <input type="text" class="form-control" value="<?= $model->so ? $model->so->no_so : '' ?>" readonly>
            <?= $form->field($model, 'id_so')->hiddenInput()->label(false) ?>
        </div>

        <div class="col-md-2">
            <label class="control-label">Working Order</label>
            <input type="text" class="form-control" value="<?= $model->worf ? $model->worf->no_planning : '' ?>"
                readonly>
            <?= $form->field($model, 'id_worf')->hiddenInput()->label(false) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'so_date')->textInput(['readonly' => true]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'worf_date')->textInput(['readonly' => true]) ?>
        </div>
        <div class="col-md-2">
            <label class="control-label">Type Production</label>
            <input type="text" class="form-control" value="<?= $model->getNamaTypeProduction() ?>" readonly>
            <?= $form->field($model, 'type_production')->hiddenInput()->label(false) ?>
        </div>
    </div>
    <h4>Detail Material</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Raw Material</th>
                <th>Length (mm)</th>
                <th>Reference Max Release</th>
                <th>Qty Produksi</th>
                <th>Scanned</th>
                <th>Total Berat Scan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <tbody>
            <?php foreach ($details as $i => $detail): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= $detail->soDetail->item->item_name ?? '-' ?></td>
                    <td><?= $detail->soDetail->item->rawMaterial->item_name ?? '-' ?></td>
                    <td><?= $detail->soDetail->length ?? '-' ?></td>
                    <?php
                    $refRelease = ceil((($detail->soDetail->length ?? 0) * ($detail->quantity_production ?? 0)) / ($detail->soDetail->item->rawMaterial->weight ?? 1));
                    ?>
                    <td><?= Yii::$app->formatter->asDecimal($refRelease, 0) ?></td>

                    <td><?= $detail->quantity_production ?></td>
                    <td>
                        <ul id="scanned-id-list-<?= $i ?>" class="list-group"></ul>
                        <!-- Akan ditambahkan dinamis lewat JS -->
                        <div id="scanned_ids_<?= $i ?>"></div>

                    </td>
                    <td><span id="total-berat-<?= $i ?>">0</span></td> <!-- Tambah ini di masing-masing baris detail -->

                    <td>
                        <div>
                            <button type="button" class="btn btn-xs btn-success btn-open-scanner"
                                data-index="<?= $i ?>">Scan
                                QR</button>
                            <button type="button" class="btn btn-xs btn-info btn-view-detail"
                                data-index="<?= $i ?>">View</button>
                        </div>
                    </td>


                </tr>
                <input type="hidden" id="expected-id-item-<?= $i ?>" value="<?= $detail->soDetail->item->id ?>">

                <input type="hidden" name="Detail[<?= $i ?>][id_worf_detail]" value="<?= $detail->id ?>" />
            <?php endforeach; ?>
        </tbody>

        </tbody>
    </table>

    <?= $form->field($model, 'notes')->widget(\dosamigos\ckeditor\CKEditor::class, [
        'options' => ['rows' => 6],
        'preset' => 'standard',
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <!-- Modal QR Scanner -->
    <div class="modal fade" id="qrScannerModal" tabindex="-1" role="dialog" aria-labelledby="qrScannerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan Barcode QR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="stopScanner()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="reader" style="width:100%;"></div>
                    <p class="text-success mt-2" id="scan-status"></p>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Gabungan Detail Material -->
    <div class="modal fade" id="combinedDetailModal" tabindex="-1" role="dialog"
        aria-labelledby="combinedDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Material - Gabungan Semua QR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="combinedDetailBody">
                    <!-- Isi melalui JS -->
                </div>
            </div>
        </div>
    </div>

</div>

<?php
$js = <<<JS
let html5QrCode = null;
let scannedIdsMap = {}; // key: index baris detail

let currentIndex = null;
let lastScanTime = 0;

function startScanner() {
    document.getElementById("scan-status").innerText = "";

    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode("reader");
    }

    Html5Qrcode.getCameras().then(cameras => {
        if (cameras && cameras.length) {
            const cameraId = cameras[0].id;

            html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (decodedText, decodedResult) => {
                    const now = Date.now();
                    if (now - lastScanTime < 2000) {
                        return; // Jangan proses kalau belum lewat 2 detik dari scan terakhir
                    }
                    lastScanTime = now;

                    scannedIdsMap[currentIndex] = scannedIdsMap[currentIndex] || [];
                    if (!scannedIdsMap[currentIndex].includes(decodedText)) {
                        scannedIdsMap[currentIndex].push(decodedText);
                        console.log("Scanned IDs Map:", JSON.stringify(scannedIdsMap, null, 2)); // Debug

                        updateScannedList();
                        let statusEl = document.getElementById("scan-status");
                        statusEl.innerText = "Scan berhasil: " + decodedText;
                        statusEl.className = "text-success";
                    } else {
                        let statusEl = document.getElementById("scan-status");
                        statusEl.innerText = "Sudah discan: " + decodedText;
                        statusEl.className = "text-danger";
                    }
                },

                (errorMessage) => {
                    // bisa log error di sini
                }
            ).catch(err => {
                document.getElementById("scan-status").innerText = "Gagal membuka kamera: " + err;
            });
        }
    }).catch(err => {
        document.getElementById("scan-status").innerText = "Tidak ada kamera ditemukan: " + err;
    });
}

function stopScanner() {
    if (html5QrCode && html5QrCode.getState() === Html5QrcodeScannerState.SCANNING) {
        html5QrCode.stop().then(() => {
            html5QrCode.clear();
            html5QrCode = null;
        }).catch(err => {
            console.error("Gagal stop scanner:", err);
        });
    } else {
        html5QrCode = null;
    }

    $('#qrScannerModal').modal('hide');
}

function updateScannedList() {
    let list = document.getElementById("scanned-id-list-" + currentIndex);
    let input = document.getElementById("scanned_ids_" + currentIndex);
    const scannedIds = scannedIdsMap[currentIndex] || [];

    list.innerHTML = "";
    input.value = scannedIds.join(",");

    let totalBerat = 0;
    let beratPromises = [];

    scannedIds.forEach((id) => {
        let li = document.createElement("li");
        li.className = "list-group-item d-flex justify-content-between align-items-start flex-column mb-1";
        li.innerHTML = `<span><strong>Loading...</strong></span>`;
        list.appendChild(li);

        // Ambil detail berdasarkan ID item detail
        let p = fetch(baseUrl + '/rollforming/release-raw-material-roll-forming/get-locaters?id=' + id)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.details.length) {
                    const item = data.details[0]; // karena hanya 1 detail per QR

                    const idItem = item.id ?? null;
                    const idMaterial = item.id_material ?? null;
                    const berat = parseFloat(item.berat_awal) || 0;
                    const expectedId = document.getElementById("expected-id-item-" + currentIndex)?.value;

                   if (idItem != expectedId) {
                        li.innerHTML = `
                            <div>
                                <span class="text-danger">Item tidak sesuai!<br>Diharapkan: \${expectedId}<br>Ditemukan: \${idItem}</span>
                            </div>`;

                        // Hapus dari data scannedIdsMap juga
                        scannedIdsMap[currentIndex] = scannedIdsMap[currentIndex].filter(itemId => itemId !== id);

                        // Hapus tampilan setelah 5 detik
                        setTimeout(() => {
                            if (li && li.parentNode) {
                                li.parentNode.removeChild(li);
                            }
                        }, 5000);

                        return;
                    }


                    totalBerat += berat;

                    li.innerHTML = `
                        <div class="d-flex justify-content-between w-100">
                            <div>
                                <strong>Material:</strong> \${idMaterial}<br>
                                <small>Berat: \${berat.toFixed(2)}</small>
                                <input type="hidden" name="Detail[\${currentIndex}][scanned_ids][]" value="\${id}">
                            </div>
                            <div>
                                <button type="button" class="btn btn-xs btn-danger ml-1" onclick="removeScannedId('\${id}', \${currentIndex})">Hapus</button>
                            </div>
                        </div>
                    `;
                } else {
                    li.innerHTML = `
                        <div>
                            <strong>ID:</strong> \${id}<br>
                            <span class="text-danger">\${data.message ?? 'Data tidak ditemukan'}</span>
                        </div>
                        <button type="button" class="btn btn-xs btn-danger ml-1" onclick="removeScannedId('\${id}', \${currentIndex})">Hapus</button>
                    `;
                }
            }).catch(error => {
                console.error('Gagal fetch data:', error);
            });

        beratPromises.push(p);
    });

    Promise.all(beratPromises).then(() => {
        document.getElementById("total-berat-" + currentIndex).innerText = totalBerat.toFixed(2);

        // Cek reference max dari kolom kelima (index kolom = 4)
        let refCell = document.querySelectorAll("tbody tr")[currentIndex].children[4];
        let refMax = parseFloat(refCell.innerText.replace(',', '')) || 0;

        // Sembunyikan tombol jika total berat sudah >= refMax
        let scanBtn = document.querySelector('.btn-open-scanner[data-index="' + currentIndex + '"]');
        if (scanBtn) {
            if (totalBerat >= refMax) {
                scanBtn.style.display = 'none';
                $('#modal-scanner').modal('hide');
                stopScanner();
            } else {
                scanBtn.style.display = '';
            }
        }

    });
}

function removeScannedId(id, index) {
    if (scannedIdsMap[index]) {
        scannedIdsMap[index] = scannedIdsMap[index].filter(item => item !== id);
        console.log("Setelah hapus:", scannedIdsMap); // Debug log
        currentIndex = index; // penting: set currentIndex sebelum update
        updateScannedList();
    }
}


document.querySelectorAll(".btn-view-detail").forEach(function(btn) {
    btn.addEventListener("click", function() {
        const index = this.getAttribute("data-index");
        showCombinedDetailModal(index);
    });
});
function showCombinedDetailModal(index) {
    let ids = document.getElementById("scanned_ids_" + index).value.split(",");
    let html = '';
    let promises = [];

    const receiptMap = {}; // key = no_good_receipt, value = array of details

    ids.forEach((id) => {
        let p = fetch(baseUrl + '/rollforming/release-raw-material-roll-forming/get-locaters?id=' + id)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.details.length) {
                    const noGR = data.no_good_receipt ?? id;

                    if (!receiptMap[noGR]) {
                        receiptMap[noGR] = [];
                    }

                    receiptMap[noGR].push(...data.details);
                }
            });
        promises.push(p);
    });

    Promise.all(promises).then(() => {
        if (Object.keys(receiptMap).length === 0) {
            html = '<p class="text-muted">Tidak ada detail ditemukan.</p>';
        } else {
            for (const noGR in receiptMap) {
                html += `
                    <h5 class="mt-3">Good Receipt: \${noGR}</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Material</th>
                                    <th>Supplier Code</th>
                                    <th>Berat Awal</th>
                                    <th>Locater</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                receiptMap[noGR].forEach(d => {
                    html += `
                        <tr>
                            <td>\${d.id_material ?? '-'}</td>
                            <td>\${d.supplier_code ?? '-'}</td>
                            <td>\${d.berat_awal ?? '-'}</td>
                            <td>\${d.locater ?? '-'}</td>
                        </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                    <hr>
                `;
            }
        }

        document.getElementById('combinedDetailBody').innerHTML = html;
        $('#combinedDetailModal').modal('show');
    });
}

// Saat tombol "Scan QR" ditekan
document.querySelectorAll(".btn-open-scanner").forEach(function(btn) {
    btn.addEventListener("click", function() {
        currentIndex = this.getAttribute("data-index");

        scannedIdsMap[currentIndex] = scannedIdsMap[currentIndex] || [];

        let existing = document.getElementById("scanned_ids_" + currentIndex).value;
        if (existing) {
            scannedIdsMap[currentIndex] = existing.split(",");
        }

        updateScannedList();
        $('#qrScannerModal').modal('show');

        setTimeout(() => {
            startScanner();
        }, 500);
    });
});

// Stop scanner saat modal ditutup
$('#qrScannerModal').on('hidden.bs.modal', function () {
    stopScanner();
});

JS;

$this->registerJs($js, \yii\web\View::POS_END);
?>