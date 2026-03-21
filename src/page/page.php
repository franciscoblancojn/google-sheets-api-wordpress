<div class="wrap">
    <h1>Google Sheets Api</h1>
    <div class="nav-tab-wrapper woo-nav-tab-wrapper">
        <button class="nav-tab nav-tab-active" data-tab="config">Configuracion</button>
        <button class="nav-tab" data-tab="test">Pruebas</button>
    </div>

    <div class="tab-content nav-tab-active" id="config">
        <?php
        require_once GOSHAP_DIR . 'src/page/sections/config.php';
        ?>
    </div>
    <div class="tab-content" id="test">
        <?php
        require_once GOSHAP_DIR . 'src/page/sections/test.php';
        ?>
    </div>
    <style>
        .tab-content:not(.nav-tab-active) {
            display: none;
        }
        .tab-content{
            padding-top: 1rem;
        }
        .nav-tab{
            cursor: pointer;
        }
    </style>
    <script>
        document.querySelectorAll('.nav-tab').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.nav-tab, .tab-content')
                    .forEach(el => el.classList.remove('nav-tab-active'));

                btn.classList.add('nav-tab-active');
                document.getElementById(btn.dataset.tab)
                    .classList.add('nav-tab-active');
            });
        });
    </script>
</div>
<?php
