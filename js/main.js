$(function () {
    let $body = $('body'),
        $containerListTags = $('#tags'),
        $tagModal = $('#tagModal');

    $body.on('submit','form[name=form_add_tag]', function (e) {
        e.preventDefault();

        let $form = $(this);

        $form.addClass('disabled');
        $form.find('button').addAjax();

        (async () => {
            let response = await fetch('/parsing_strin_on_tags_data_description/ajax/addTag.php', {
                method: 'POST',
                headers: {
                    "Content-type": "application/x-www-form-urlencoded; charset=UTF-8; application/json"
                },
                body: $form.serialize()
            });

            if (response.ok) {
                let result = await response.json();

                if (result.success) {
                    await getListTags($form);
                    clearLaoderOfAjaxForm($form);
                }else{
                    alert(result.error);
                    clearLaoderOfAjaxForm($form);
                }
            }else{
                alert('problem with add tag');
                clearLaoderOfAjaxForm($form);
            }
        })();
    });

    clearLaoderOfAjaxForm = ($form) => {
        $form.removeClass('disabled');
        $form.find('button').removeAjax();
        $form.find('input').val('');
    };

    $tagModal.on('show.bs.modal', function (e) {
        let $clickedOn = $(e.relatedTarget),
            $content = $tagModal.find('.modal-body');

        $content.addAjax();
        getTagInfo($clickedOn.data('tag-id'), $(this).find('session-id').val()).then(result => $content.html(result.html));
    });

    async function getListTags($form){
        let response = await fetch('/parsing_strin_on_tags_data_description/ajax/getTags.php',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8; application/json;charset=utf-8'
            },
            body:'ajax=Y&session-id=' + $form.find('[name=session-id]').val()
        });

        if (response.ok){
            let result = await response.json();
            $containerListTags.html(result.html);
        }else{
            alert('problem with get all tags')
        }
    }

    async function getTagInfo(tagID, session){
        let response = await fetch('/parsing_strin_on_tags_data_description/ajax/getTagInfo.php',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8; application/json;charset=utf-8'
            },
            body: 'tagID=' + tagID + '&ajax=Y&session-id='+ session
        });

        if (response.ok){
            return await response.json();
        }else{
            alert('problem with get tag info')
            return response;
        }
    }

    $.fn.addAjax = function () {
        let $this = $(this),
            htmlLoader = '<div class="spinner-border" role="status">\n' +
                '  <span class="sr-only">Loading...</span>\n' +
                '</div>';
        $this.data('html',$this.html()).html(htmlLoader);
    };

    $.fn.removeAjax = function () {
        let $this = $(this);
        $this.html($this.data('html'));
    }
});