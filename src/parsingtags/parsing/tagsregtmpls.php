<?php
/**
 * Copyright (c) 2020. Martynov AV email: sandysman@mail.ru
 */

namespace ParsingTags\Parsing;

class TagsRegTmpls
{
    public static $tmplTagWithDesc = '/<[a-zA-Z]*?:[\S][a-zA-Z\s]*?>[\w|\s]+[\w\s]*?<\/[a-zA-Z]*?>/u';
    public static $tmplTagWithoutDesc = '/<[a-zA-Z]*?>[\w|\s]+[\w\s]*?<\/[a-zA-Z]*?>/u';

    public static $tmplGetTagWithoutDesc = '/>[\w|\s]+[\w\s]*?<\/(.*?)>/u';
    public static $tmplGetTagWithDesc = '/:[\S][a-zA-Z\s]*?>[\w|\s]+[\w\s]*?<\/(.*?)>/u';

    public static $tmplGetTagDesc = '/>[\w|\s]+[\w\s]*?<\/(.*?)>/u';
    public static $tmplGetTagDescReplaceBegin = '/<[a-zA-z]*?:/';

    public static $tmplGetTagData = '/<.*?>/';
}