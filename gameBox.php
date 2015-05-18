    <div id="selectUrlBox" hidden="hidden" style="height:100%; width:100%; z-index: 2003; position: fixed; left:0; top:0;">
    </div> 

<?php
        if(isset($game)){
            $id = $game['_id'];
            $name = $game['name'];
            $type = $game['type'];
            $des = $game['des'];
            $category = @$game['category'];
            $icon = @$game['icon'];
            $url = @$game['url'];
            $priority = $game['priority'];
            $priorityOverOn = $game['priorityOverOn'];
            $publishOn = $game['publishOn'];
            $galleryArray = @$game['gallery'];
            $background = @$game['background'];

            $gallery = "";
            if(!empty($galleryArray)){
                foreach ($galleryArray as $url) {
                    if($gallery == ""){
                        $gallery = $url;
                    }else{
                        $gallery = $gallery. "," . $url;
                    }
                }
            }

            $form_action = "/game_center/game/modify";
            $form_title = "修改游戏";
        }else{
            $form_action = "/game_center/game/add";
            $form_title = "添加游戏";
        }
?>

    <div class="panel panel-default gh-panel" style="width:65%; max-height:90%; overflow-y: scroll;">
        <form role="form" action="<?php echo $form_action;?>" method="post">
            <div class="panel-heading gh-panel-heading" style="width: 65%;">
                <i class="fa fa-edit fa-fw"></i> <?php echo $form_title;?>
                <button type="submit" class="btn btn-success gh-submit">确定</button>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body gh-panel-body">

                    <div class="row">
                        <div class="form-group col-md-5">
                            <div class="form-group">
                                <label>游戏名称</label>
                                <input class="form-control" name="name" placeholder="游戏名称" <?php if(!empty($name)) echo 'value="'.$name.'"'; ?> >
                            </div>
                            <div class="form-group">
                                <label>类别</label>                      
                                <select class="form-control" name="category" placeholder="游戏类别" >
                                    <option value="online" <?php if(!empty($category) && $category=="online") echo "selected";?> >网络游戏</option>
                                    <option value="local" <?php if(!empty($category) && $category=="local") echo "selected";?> >单机游戏</option>
                                <select>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label>分类</label>
                                <select class="form-control" name="type" placeholder="游戏分类" >
                                    <option value="未分类">未分类</option>
                                    <?php
                                    foreach ($gameTypes as $gameType) {
                                        if($type == $gameType){
                                            echo '<option value="'.$gameType.'" selected = "selected" >'.$gameType.'</option>';
                                        }else{
                                            echo '<option value="'.$gameType.'">'.$gameType.'</option>';                                   
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-5 col-md-offset-2">
                            <label>图标</label>
                            <div class="clear"></div>
                            <div id="game_icon_render">
                                <?php if(!empty($name)) echo '<img id="game_icon_img" src="'.$icon.'">'; ?>
                            </div>
                            <input type="file" id="game_icon_upload" >
                            <input name="icon" id="game_icon" hidden='hidden' <?php if(!empty($icon)) echo 'value="'.$icon.'"'; ?> >
                            <div class="clear"></div>
                        </div>
                       
                    </div>

                    <div class="form-group">
                        <label>简介</label>
                        <input class="form-control" name="des" placeholder="游戏简介" <?php if(!empty($des)) echo 'value="'.$des.'"'; ?> >
                    </div>
                    <div class="form-group">
                        <label>发布时间</label>
                        <input class="form-control" name="publishOn" type="text" readOnly="true" placeholder="<?php echo date('Y-m-d H:i:s', strtotime('now'));?>" style="cursor: pointer;" 
                            onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss', skin:'twoer', lang:'zh-cn'})" <?php if(!empty($publishOn)) echo 'value="' . @date('Y-m-d H:i:s', $publishOn->sec) . '"';?> >
                    </div>

                    <div class="row">
                        <div class="form-group col-md-5">
                            <label>优先级</label>
                            <input class="form-control" name="priority" placeholder="优先级" type="number" <?php if(!empty($priority)) echo 'value="'.$priority.'"'; ?> >
                        </div>
                        <div class="form-group col-md-7">
                            <label>优先级过期时间</label>
                            <input class="form-control" name="priorityOverOn" type="text" readOnly="true" placeholder="点击选择时间，不选表示不过期" style="cursor: pointer;" 
                                onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss', skin:'twoer', lang:'zh-cn'})" <?php if(!empty($priorityOverOn)) echo 'value="' . @date('Y-m-d H:i:s', $priorityOverOn->sec) . '"';?> >
                        </div>                    
                    </div>
                    <div class="form-group" style="margin-top: 20px;">
                       <label>下载地址</label>
                           <div class="row row_adjust" id="apk_control">
                                <div class="row apk_control_line" id="apk_control_select" style="border: 0; padding: 0;">
                                    <div class="col-md-3" style="font-weight: 100; line-height: 30px; padding: 0; width: 6em;">
                                        添加方式：
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" onchange="select_apk_control(this);">
                                            <option value="1">上传</option>
                                            <option value="2">填写</option>        
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row row_adjust" id="apk_fill_in" hidden>
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <!-- <input class="form-control" name="url" placeholder="URL地址"> -->
                                        <input class="form-control download-url-select" id="url" name="apk_url" readOnly="true"  placeholder="点击选择URL地址" >                         
                                    </div>

                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <input class="form-control" name="apk_package" placeholder="包名">
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" name="apk_version" placeholder="版本号">
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" name="apk_size" placeholder="大小">
                                    </div>
                                </div>             
                            </div>

                            <!-- <div class="clear"></div> -->
                            <div class="row" id="apk_info">
                                <div class="col-md-12">
                                    <input type="file" id="game_apk_upload" >
                                    <input class="form-control form-group" id="apk_url_display" readOnly="true"  <?php if(!empty($apk) && !empty($apk['url'])){echo 'value="'.$apk['url'].'"';}else{ echo 'style="display:none"';} ?> >
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>包名</th>                                         
                                                <th>版本</th>
                                                <th>大小</th>
                                                <th>CDN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="package_info"><?php if(!empty($apk)){echo $apk['package'];}else{echo '暂无';} ?></td>
                                                <td id="version_info"><?php if(!empty($apk)){echo $apk['version'];}else{echo '暂无';} ?></td>
                                                <td id="size_info"><?php if(!empty($apk)){echo $apk['size'];}else{echo '暂无';} ?></td>
                                                <td id="cdn_info"><?php if(!empty($apk)){echo $apk['status'];}else{echo '暂无';} ?></td>
                                            </tr>
                                    </table>
                                </div>
                            </div>

                        <input id="package" name="package" hidden="hidden" <?php if(!empty($apk)) echo 'value="'.$apk['package'].'"'?> >
                        <input id="version" name="version" hidden="hidden" <?php if(!empty($apk)) echo 'value="'.$apk['version'].'"'?> >
                        <input id="size" name="size" hidden="hidden" <?php if(!empty($apk)) echo 'value="'.$apk['size'].'"'?> >
                        <input id="apk_control_status" name="apk_control_status" hidden="hidden" value="UPLOAD">
                    </div>
                   <div class="form-group">
                        <label>背景</label>
                        <div class="clear"></div>
                        <div id="game_background_render">
                            <?php if(!empty($background)) echo '<img id="game_background_img" src="'.$background.'">'; ?>
                        </div>
                        <input type="file" id="game_background_upload" >
                        <input name="background" id="background" hidden='hidden' <?php if(!empty($background)) echo 'value="'.$background.'"'; ?> >
                        <div class="clear"></div>
                    </div>
                   <div class="form-group">
                        <label>图集</label>
                        <div id="gallery_container">
                            <div id="gallery_show"></div>
                        </div>
                        <div class="clear"></div>
                        <input type="file" id="game_gallery_upload">
                        <input id="gallery" name="gallery" hidden="hidden" <?php if(!empty($gallery)) echo 'value="'.$gallery.'"'; ?> >
                        <div class="clear"></div>
                    </div>
                    <?php if(!empty($id)) echo '<input name="id" hidden="hidden" value="'.$id.'">'; ?>
                    <input name="apk_id" id="apk_id" hidden="hidden" value= <?php if(!empty($apk)) echo '"'.$apk['_id'].'"'; ?> >              
            </div>
            <!-- /.panel-body -->
        </form>
    </div>

<!-- 

    <script src="/game_center/assets/uploadify/jquery.uploadify.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/game_center/assets/uploadify/uploadify.css" />
    <script type="text/javascript" charset="utf-8" src="/game_center/assets/My97DatePicker/WdatePicker.js"></script>
 -->
    <script type="text/javascript">

        var gallery = new Array();
        
        <?php if(!empty($galleryArray)){?>

        var galleryCurrent = <?php echo json_encode($galleryArray);?>;

        gallery = galleryCurrent;
        showGalleryPic(gallery);

        <?php }?>
        function showGalleryPic(array){
            var html = "";
            for (var i = array.length - 1; i >= 0; i--) {
                html = '<div style="margin:8px; float:left;"><img id="game_gallery_img" src="'+array[i]+'"><a href="javascript:void(0);" onclick="deletePic(this)">删除</a></div>' + html;
            };
            $('#gallery_show').css("width", 164 * array.length + "px");
            $('#gallery_show').html(html);
            if(array.length == 0){
                $('#gallery_container').hide();
            }else{
                $('#gallery_container').show();
            }
        }

        function deletePic(obj){
            var url = $(obj).siblings("img").attr("src");
            for (var i = gallery.length - 1; i >= 0; i--) {
                if(gallery[i] == url){
                    gallery.splice(i, 1);
                }
            };
            $('#gallery').val(gallery.toString());
            showGalleryPic(gallery);
        }

        $(function() {
            $('#game_icon_upload').uploadify({
                'multi'    : false,
                'swf'      : '/game_center/assets/uploadify/uploadify.swf',
                'uploader' : '/game_center/func/upload?type=icon',
                'width' : 80,
                'height' : 80,
                'buttonText' : '+',
                'onUploadSuccess' : function(file, data, response) {
                    // alert(data);
                    var res = $.parseJSON(data);
                    if(res.url){
                        // uploadCDN(res._id, 'pic', res.localUrl);
                        $('#game_icon').val(res.url);
                        $('#game_icon_render').html('<img id="game_icon_img" src="'+res.url+'">');
                        $('#game_icon_upload').removeClass('gh-game-icon').addClass('gh-game-icon-modify');
                    }
                }
            });
            <?php if(!empty($name)){?> 
                $('#game_icon_upload').addClass('gh-game-icon-modify');
            <?php }else{?>
                $('#game_icon_upload').addClass('gh-game-icon');
            <?php }?>
            $('#game_background_upload').uploadify({
                'multi'    : false,
                'swf'      : '/game_center/assets/uploadify/uploadify.swf',
                'uploader' : '/game_center/func/upload?type=icon',
                'onUploadSuccess' : function(file, data, response) {
                    var res = $.parseJSON(data);
                    if(res.url){
                        // uploadCDN(res._id, 'pic', res.url);
                        $('#background').val(res.url);
                        $('#game_background_render').html('<img id="game_background_img" src="'+res.url+'">');
                    }
                }
            });
            $('#game_background_upload').addClass('gh-game-bg');


           $('#game_gallery_upload').uploadify({
                'multi'    : true,
                'swf'      : '/game_center/assets/uploadify/uploadify.swf',
                'uploader' : '/game_center/func/upload?type=icon',

                'onUploadSuccess' : function(file, data, response) {
                    var res = $.parseJSON(data);
                    if(res.url){
                        // uploadCDN(res._id, 'pic', res.localUrl);
                        if($.inArray(res.url, gallery) == -1){
                            gallery.push(res.url);
                            $('#gallery').val(gallery.toString());
                           showGalleryPic(gallery);
                        }
                   }
                }
            });
           $('#game_gallery_upload').addClass('gh-game-gallery');

           $('#game_apk_upload').uploadify({
                'multi'    : false,
                'swf'      : '/game_center/assets/uploadify/uploadify.swf',
                'uploader' : '/game_center/util/upload?type=apk',
                'onUploadSuccess' : function(file, data, response) {
                    apk_upload_complete();
                    var data = $.parseJSON(data);
                    var FILE_URL = data.localUrl
                    // $('#url').val(data.fileName);
                    // alert("/game_center/util/aapt?app="+res.fileName);
                    $.get("/game_center/util/aapt?apk="+FILE_URL, function(data){
                        // alert(data);
                        var data = $.parseJSON(data);
                        $('#package_info').html(data.package);
                        $('#version_info').html(data.version);
                        $('#size_info').html(data.size);
                        $('#apk_info').css('display', 'block');
                        $('#cdn_info').html('正在上传...');

                        $('#package').val(data.package);
                        $('#version').val(data.version);
                        $('#size').val(data.size);
                        // alert(data.id);
                        $('#apk_id').val(data.id);

                        uploadCDN(data.id, 'apk', FILE_URL);
                        // request = $.get("/game_center/util/uploadCDN?file="+FILE_URL +"&type=apk&id="+data.id);
                        // window.setTimeout(slowAlert, 20);
                        // if(request) request.abort();

                    });
                }
            });
        });

        $(".download-url-select").click(function(e) {
            $.get("/game_center/plugin/loadDownloadUrlBox", function(res){
                $('#selectUrlBox').html(res);
                $('#selectUrlBox').modal('show');
            });
            
        });    

        function select_download_url(){
            var url = $('input[name="download_url_select"]:checked').val();

            if(url){
                $('#url').val(url);
                $('#selectUrlBox').modal('hide');
            }          
        }

        function click_apk_fill_in(){
            $('#apk_info').hide();
            $('#apk_fill_in').show();
            $('#apk_control_status').val('FILL_IN');
        }

        function click_apk_upload(){
            $('#apk_info').show();
            $('#apk_fill_in').hide();
            $('#apk_control_status').val('UPLOAD');
        }

        function apk_upload_complete(){
            $('#apk_url_display').hide();      
            $('#apk_control_status').val('UPLOAD');
        }

        function select_apk_control(obj) {
            if(obj.value == '1'){
                click_apk_upload();
            }else{
                click_apk_fill_in();
            }
        }

    </script>