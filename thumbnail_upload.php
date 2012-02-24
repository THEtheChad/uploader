<?php
function display_frontpage_details($post, $meta){
    $frontpage = get_post_meta($post->ID, 'frontpage', true);
    $thumbnail = $frontpage['thumbnail'];
    $background = $thumbnail? 'url('.$thumbnail.')' : '#ccc';
?>
    <table>
        <tr>
            <th>
                Thumbnail
                <input data-target='#frontpage-thumbnail' class='upload-button button-primary' type='submit' value='Upload'/>
                <input id='frontpage-thumbnail' name='frontpage-thumbnail' type='hidden' value='<?php echo $thumbnail; ?>'/>
            </th>
            <td>
                <div id='frontpage-thumbnail-preview' style='margin:auto;height:100px;width:100px;background-size:contain;background:no-repeat center <?php echo $background; ?>'>
            </td>
        </tr>
        <tr>
            <th>iPhone Link</th>
            <td><input id='frontpage-iphone-link' name='frontpage-iphone-link' type='text' value='<?php echo $frontpage['iphone']; ?>'/></td>
        </tr>
        <tr>
            <th>App Link</th>
            <td><input id='frontpage-app-link' name='frontpage-app-link' type='text' value='<?php echo $frontpage['app-link']; ?>'/></td>
        </tr>
    </table>
<?php
}

add_action('save_post', 'meta_box_save');
function meta_box_save($post_id){
   
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    $frontpage['thumbnail'] = $_POST['frontpage-thumbnail'];
    $frontpage['iphone'] = $_POST['frontpage-iphone-link'];
    $frontpage['app-link'] = $_POST['frontpage-app-link'];

    update_post_meta($post_id, 'frontpage', $frontpage);
}

add_action('admin_head', 'image_list_ui_scripts');
function image_list_ui_scripts(){
?>
<script>
(function($){
    $(function(){
        var $buttons = $('input.upload-button'),
            idx = $buttons.length;
        
        while(idx){
            $buttons.eq(--idx).click(function(e){
                var $button = $(this),
                    target_id = $button.data('target'),
                    $target = $(target_id),
                    $preview = $(target_id + '-preview');

                tb_show('', 'media-upload.php?TB_iframe=true');
                window.send_to_editor = function(html){
                    var url = $(html).attr('href');
    
                    $target.val(url);
                    $preview.css({'background':'url(' + url + ') no-repeat center'});
    
                    tb_remove();
                };
    
                e.preventDefault();
            });
        }
    });
})(jQuery);
</script>
<?php
}