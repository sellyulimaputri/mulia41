<?php
namespace app\widgets;

use yii\helpers\Html;
use yii\widgets\InputWidget;
use yii\web\View;

class SearchableSelect extends InputWidget
{
    public $items = [];
    public $options = [];
    public $containerOptions = [];
    public $prompt = null;
    public $width = null;

    public function init()
    {
        parent::init();
        $this->registerAssets();
        $this->options['class'] = 'searchable-select-input';
        $this->containerOptions['class'] = isset($this->containerOptions['class'])
            ? 'searchable-select-container ' . $this->containerOptions['class']
            : 'searchable-select-container';

        $this->containerOptions['data-prompt'] = $this->prompt ?? 'Select an option';
    }

    public function run()
    {
        $value = $this->hasModel()
            ? Html::getAttributeValue($this->model, $this->attribute)
            : $this->value;

        $input = $this->hasModel()
            ? Html::activeHiddenInput($this->model, $this->attribute, $this->options)
            : Html::hiddenInput($this->name, $value, $this->options);

        $selectedText = ($value !== null && isset($this->items[$value]))
            ? $this->items[$value]
            : ($this->prompt ?? 'Select an option');

        $html = Html::beginTag('div', $this->containerOptions);
        $html .= Html::beginTag('div', ['class' => 'select-wrapper']);
        $html .= Html::tag('div', $selectedText, ['class' => 'selected-value form-control']);
        $html .= Html::tag('div', '&times;', [
            'class' => 'clear-selected',
            'style' => 'position: absolute; right: 35px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #999;',
        ]);
        $html .= Html::beginTag('div', ['class' => 'select-dropdown']);
        $html .= Html::textInput('search', '', [
            'class' => 'search-input',
            'placeholder' => 'Search...'
        ]);

        $html .= Html::beginTag('div', ['class' => 'options-container']);
        foreach ($this->items as $key => $label) {
            $html .= Html::tag('div', $label, [
                'class' => 'option' . ($value == $key ? ' selected' : ''),
                'data-value' => $key
            ]);
        }
        $html .= Html::endTag('div'); // options-container
        $html .= Html::endTag('div'); // select-dropdown
        $html .= Html::endTag('div'); // select-wrapper
        $html .= $input;
        $html .= Html::endTag('div');

        return $html;
    }

    protected function registerAssets()
    {
        $view = $this->getView();
        $view->registerCss($this->getCSS());
        $view->registerJs($this->getJS(), View::POS_READY);
    }

    protected function getCSS()
    {
        $widthValue = $this->width ?? '310px';

        return <<<CSS
        .searchable-select-container {
            position: relative;
            width: 100%;
        }

        .searchable-select-container .select-wrapper {
            height: 100%;
        }

        .searchable-select-container .selected-value {
            width: 100%;
            cursor: pointer;
            display: flex;
            align-items: center;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            padding-right: 32px;
        }

        .searchable-select-container .clear-selected:hover {
            color: #e53e3e;
        }

        .searchable-select-container .selected-value::after {
            content: '';
            position: absolute;
            right: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #4a5568;
            transition: transform 0.2s;
        }

        .searchable-select-container.active .selected-value::after {
            transform: translateY(-50%) rotate(180deg);
        }

        .searchable-select-container .select-dropdown {
            position: fixed;
            background: #fff;
            border: 1px solid #e0e6ed;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
            margin-top: 4px;
        }

        .searchable-select-container .search-input {
            margin: 0.5rem;
            width: calc(100% - 1rem);
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            line-height: 1.5;
            color: #4a5568;
            background-color: #fff;
            border: 1px solid #e0e6ed;
            border-radius: 4px;
        }

        .searchable-select-container .options-container {
            max-height: 200px;
            overflow-y: auto;
            padding: 0.5rem 0;
        }

        .searchable-select-container .option {
            padding: 0.5rem 0.875rem;
            cursor: pointer;
            font-size: 0.875rem;
            color: #4a5568;
            transition: background-color 0.2s;
        }

        .searchable-select-container .option:hover,
        .searchable-select-container .option.selected {
            background-color: #f1f5f9;
        }

        .searchable-select-container .option.focused {
            background-color: #e2e8f0;
        }

        .searchable-select-container.active .select-dropdown {
            display: block;
        }

        .master-form .input-row > .searchable-select-container {
            flex: 0 0 {$widthValue};
            max-width: 100%;
        }

        .master-form .input-row > div:not(.searchable-select-container) {
            flex: 1;
            min-width: 0;
        }
CSS;
    }

    protected function getJS()
    {
        return <<<JS
        $(document)
            .on('click', '.searchable-select-container .selected-value', function(e) {
                const container = $(this).closest('.searchable-select-container');
                const dropdown = container.find('.select-dropdown');
                const searchInput = container.find('.search-input');

                e.stopPropagation();
                $('.searchable-select-container').not(container).removeClass('active');
                container.toggleClass('active');

                if (container.hasClass('active')) {
                    const rect = container[0].getBoundingClientRect();
                    dropdown.css({
                        top: rect.bottom + 'px',
                        left: rect.left + 'px',
                        width: rect.width + 'px'
                    });
                    searchInput.val('').focus();
                    container.find('.option').show().removeClass('focused');
                }
            })
            .on('click', '.searchable-select-container .option', function(e) {
                const container = $(this).closest('.searchable-select-container');
                const selectedValue = container.find('.selected-value');
                const hiddenInput = container.find('.searchable-select-input');
                const options = container.find('.option');

                e.stopPropagation();
                const value = $(this).data('value');
                const text = $(this).text();
                selectedValue.text(text);
                hiddenInput.val(value).trigger('change');
                container.removeClass('active');
                options.removeClass('selected focused');
                $(this).addClass('selected');
            })
            .on('input', '.searchable-select-container .search-input', function() {
                const container = $(this).closest('.searchable-select-container');
                const searchText = $(this).val().toLowerCase();
                const options = container.find('.option');

                options.each(function() {
                    const text = $(this).text().toLowerCase();
                    const match = text.includes(searchText);
                    $(this).toggle(match).removeClass('focused');
                });

                const firstVisible = options.filter(':visible').first();
                firstVisible.addClass('focused');
            })
            .on('keydown', '.searchable-select-container .search-input', function(e) {
                const container = $(this).closest('.searchable-select-container');
                const options = container.find('.option:visible');
                const current = container.find('.option.focused');
                let next;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    next = current.length ? current.nextAll(':visible').first() : options.first();
                    if (next.length) {
                        current.removeClass('focused');
                        next.addClass('focused');
                        next[0].scrollIntoView({ block: 'nearest' });
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    next = current.length ? current.prevAll(':visible').first() : options.last();
                    if (next.length) {
                        current.removeClass('focused');
                        next.addClass('focused');
                        next[0].scrollIntoView({ block: 'nearest' });
                    }
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (current.length) {
                        current.click();
                    }
                }
            })
            .on('click', '.searchable-select-container .clear-selected', function(e) {
                e.stopPropagation();
                const container = $(this).closest('.searchable-select-container');
                const hiddenInput = container.find('.searchable-select-input');
                const selectedValue = container.find('.selected-value');
                const options = container.find('.option');

                hiddenInput.val('').trigger('change');
                selectedValue.text(container.data('prompt') || 'Select an option');
                options.removeClass('selected');
            })
            .on('click', function(e) {
                if (!$('.searchable-select-container.active').is(e.target) &&
                    $('.searchable-select-container.active').has(e.target).length === 0) {
                    $('.searchable-select-container.active').removeClass('active');
                    $('.searchable-select-container .option.focused').removeClass('focused');
                }
            });
JS;
    }
}