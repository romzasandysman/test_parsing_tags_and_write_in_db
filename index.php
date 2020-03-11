<!DOCTYPE html>
<html>
<head>
    <?php
        use ParsingTags\db\dbConfigConnection;
        use ParsingTags\HtmlViewTags\viewTags;
        use ParsingTags\linkGetDataDB;

        include_once 'vendor/autoload.php';
    ?>
    <meta charset="utf-8">
    <title>Кликкер</title>
    <link href="/parsing_strin_on_tags_data_description/css/bootstrap/bootstrap-grid.min.css" type="text/css"
          rel="stylesheet">
    <link href="/parsing_strin_on_tags_data_description/css/bootstrap/bootstrap-reboot.min.css" type="text/css"
          rel="stylesheet">
    <link href="/parsing_strin_on_tags_data_description/css/bootstrap/bootstrap.min.css" type="text/css"
          rel="stylesheet">
    <link href="/parsing_strin_on_tags_data_description/css/main.css" type="text/css" rel="stylesheet">
    <script src="/parsing_strin_on_tags_data_description/js/vendor/jquery.js"></script>
    <script src="/parsing_strin_on_tags_data_description/js/vendor/bootstrap/bootstrap.min.js"></script>
    <script src="/parsing_strin_on_tags_data_description/js/main.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>Парсинг тегов</h3>
        </div>
    </div>
    <div class="row form">
        <div class="col-md-12">
            <form method="post" name="form_add_tag">
                <?php session_start(); ?>
                <input name="session-id" type="hidden" value="<?php echo session_id() ?>">
                <input name="ajax" type="hidden" value="Y">
                <div class="form-group">
                    <input class="form-control" type="text" name="tag_data">
                </div>
                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
        </div>
    </div>
    <div class="row list-tags">
        <div class="offset-md-2 col-md-4">Теги в базе</div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">ID тега</div>
                <div class="col-md-4">Имя тега</div>
            </div>
        </div>
        <div class="col-md-12" id="tags">
            <?php try {
                $linkDataDB = new linkGetDataDB(dbConfigConnection::$arConnectionData);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
            if ($arTags = $linkDataDB->getRowsOfTable($linkDataDB->getObTable('tags'))){
                $objViewTags = new viewTags('');
                echo $objViewTags->showTags($objViewTags->prepareArTagsForShow($arTags, [
                            $linkDataDB->getObTable('tags')->getColumnNameName() => 'NAME',
                            $linkDataDB->getObTable('tags')->getColumnNameId() => 'ID',
                        ]
                    )
                );
            }?>
        </div>
    </div>
</div>

<div class="modal fade" id="tagModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <input name="session-id" type="hidden" value="<?php echo session_id() ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Детальная информация по тегу</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ок</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
