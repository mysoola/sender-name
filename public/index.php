<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>

    <div class="container">
        <div class="users-list mt-3 js-users-list">
            <?php
            $APPLICATION->IncludeComponent(
                'public:users.list',
                '',
                [
                    'CACHE_TYPE' => 'A',
                    'CACHE_TIME' => 0,
                ],
                false
            );
            ?>
        </div>
    </div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>