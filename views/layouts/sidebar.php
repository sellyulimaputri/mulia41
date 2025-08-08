<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?= $assetDir ?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Mulia 41</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php echo \hail812\adminlte\widgets\Menu::widget([
                // ['label' => 'Level1'],
                // [
                "items" => [
                    [
                        "label" => "Master",
                        "icon" => "box", // atau ikon lain sesuai FontAwesome
                        "items" => [
                            [
                                "label" => "Master Item Finish Good",
                                "icon" => "box",
                                "url" => ["master-item/index"],
                            ],
                            [
                                "label" => "Master Raw Material",
                                "icon" => "box",
                                "url" => ["master-raw-material/index"],
                            ],
                            [
                                "label" => "Business Partner",
                                "icon" => "users",
                                "url" => ["business-partner/index"],
                            ],
                        ],
                    ],
                    [
                        "label" => "Sales Order",
                        "icon" => "cart-plus", // atau ikon lain sesuai FontAwesome
                        "items" => [
                            [
                                "label" => "Import Sales Order",
                                "icon" => "file-invoice",
                                "url" => [
                                    "sales/sales-order-standard-import/index",
                                ],
                            ],
                        ],
                    ],
                    [
                        "label" => "Roll Forming",
                        "icon" => "industry", // atau ikon lain sesuai FontAwesome
                        "items" => [
                            [
                                "label" => "Working RF",
                                "icon" => "box",
                                "url" => [
                                    "rollforming/working-order-roll-forming/index",
                                ],
                            ],
                            [
                                "label" => "Release Raw Material RF",
                                "icon" => "box",
                                "url" => [
                                    "rollforming/release-raw-material-roll-forming/index",
                                ],
                            ],
                            [
                                "label" => "Production RF",
                                "icon" => "box",
                                "url" => [
                                    "rollforming/production-roll-forming/index",
                                ],
                            ],
                            [
                                "label" => "Cost Production RF",
                                "icon" => "box",
                                "url" => [
                                    "rollforming/cost-production-roll-forming/index",
                                ],
                            ],
                        ],
                    ],
                    [
                        "label" => "Purchase Order",
                        "icon" => "store", // atau ikon lain sesuai FontAwesome
                        "items" => [
                            [
                                "label" => "Finalize PO",
                                "icon" => "file-invoice",
                                "url" => ["purchase-order/index"],
                            ],

                            [
                                "label" => "Good Reciept",
                                "icon" => "cart-arrow-down",
                                "url" => ["good-reciept/index"],
                            ],
                        ],
                    ],

                    // [
                    //     'label' => 'Starter Pages',
                    //     'icon' => 'tachometer-alt',
                    //     'badge' => '<span class="right badge badge-info">2</span>',
                    //     'items' => [
                    //         ['label' => 'Active Page', 'url' => ['site/index'], 'iconStyle' => 'far'],
                    //         ['label' => 'Inactive Page', 'iconStyle' => 'far'],
                    //     ]
                    // ],
                    // ['label' => 'Simple Link', 'icon' => 'th', 'badge' => '<span class="right badge badge-danger">New</span>'],
                    // ['label' => 'Yii2 PROVIDED', 'header' => true],
                    // ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                    // ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                    // ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],
                    // ['label' => 'MULTI LEVEL EXAMPLE', 'header' => true],
                    // ['label' => 'Level1'],
                    // [
                    //     'label' => 'Level1',
                    //     'items' => [
                    //         ['label' => 'Level2', 'iconStyle' => 'far'],
                    //         [
                    //             'label' => 'Level2',
                    //             'iconStyle' => 'far',
                    //             'items' => [
                    //                 ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                    //                 ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
                    //                 ['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle']
                    //             ]
                    //         ],
                    //         ['label' => 'Level2', 'iconStyle' => 'far']
                    //     ]
                    // ],
                    // ['label' => 'Level1'],
                    // ['label' => 'LABELS', 'header' => true],
                    // ['label' => 'Important', 'iconStyle' => 'far', 'iconClassAdded' => 'text-danger'],
                    // ['label' => 'Warning', 'iconClass' => 'nav-icon far fa-circle text-warning'],
                    // ['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info'],
                ],
            ]); ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
