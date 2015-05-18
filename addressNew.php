<div class="panel panel-default gh-panel" style="width: 50%; height: 80%;">

<div class="panel-heading gh-panel-heading" style="width: 50%;">
    <i class="fa fa-edit fa-fw"></i>添加地址
    <button type="submit" class="btn btn-success gh-submit" onclick="addressConfirm()">确定</button>
</div>
<!-- /.panel-heading -->
<div class="panel-body gh-panel-body">   

    <form role="form" id="apkForm">

    <div class="row row_adjust" id="apk_control">

        <div class="row apk_control_line" id="apk_control_select">
            <div class="col-md-3" style="line-height: 30px; padding: 0; width: 5em;">
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


    <div class="row row_adjust" id="apk_fill_in" hidden style="margin-top: 1.5em;">
        <div class="row form-group">
            <div class="col-md-3">
                <select name="platform1" class="form-control">
                    <?php
                        foreach ($platforms as $key => $value) {
                            echo '<option value ="'.$key.'">'.$value.'</option>';
                        }
                    ?>
                </select>
            </div>

            <div class="col-md-9">
                <!-- <input class="form-control" name="url" placeholder="URL地址"> -->
                <input class="form-control" id="url" name="apk_url" placeholder="填写URL地址" >                         
            </div>

        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <input class="form-control" id="package" name="apk_package" placeholder="包名">
            </div>
            <div class="col-md-3">
                <input class="form-control" id="version" name="apk_version" placeholder="版本号">
            </div>
            <div class="col-md-3">
                <input class="form-control" id="size" name="apk_size" placeholder="大小">
            </div>
        </div>             
    </div>

    <!-- <div class="clear"></div> -->
    <div class="row row_adjust" id="apk_upload" style="margin-top: 1.5em;">
        <div class="row form-group">

            <div class="col-md-3" style="width: 28%">
                <select name="platform2" class="form-control">
                    <?php
                        foreach ($platforms as $key => $value) {
                            echo '<option value ="'.$key.'">'.$value.'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="file" id="game_apk_upload">           
            </div>

        </div>

        <div class="row form-group">

            <div class="col-md-12">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>包名</th>                                         
                            <th>版本</th>
                            <th>大小</th>
                            <th>CDN</th>
                        </tr>
                    </thead>
                    <tbody id="plugin_info">
                        <tr id="plugin_holder">
                            <td id="package_info">暂无</td>
                            <td id="version_info">暂无</td>
                            <td id="size_info">暂无</td>
                            <td id="cdn_info">暂无</td>
                        </tr>
                </table>
            </div>

        </div>

    </div>

        <input id="apk_control_status" name="apk_control_status" hidden="hidden" value="UPLOAD">
        <input name="apk_id" id="apk_id" hidden="hidden">

    </form>

</div>

</div>


<script type="text/javascript">

    function addressConfirm(){

        var status = $('#apk_control_status').val();

        if( status == 'FILL_IN' ) {
            if( $('#url').val() == '' 
                && $('#package').val() == ''
                && $('#version').val() == ''
                && $('#size').val() == ''
                ){
                return;
            }
        }

        $.post(
            "/game_center/plugin/createApk", 
            $("#apkForm").serialize(), 
            function(id){
                if(id != 'ERROR'){
                    // pluginBox中的js方法
                    addAddress(id);
                    $("#addAddressBox").modal('hide');
                } 
            }
        );
    }

    function click_apk_fill_in(){
        $('#apk_upload').hide();  
        $('#apk_fill_in').show();
        $('#apk_control_status').val('FILL_IN');
    }

    function click_apk_upload(){
        $('#apk_fill_in').hide();
        $('#apk_upload').show();     
        $('#apk_control_status').val('UPLOAD');
    }

    function apk_upload_complete(){
        $('#apk_control_select').hide();
        $('#apk_control_upload').show();
        // $('#apk_url_display').hide();      
        $('#apk_control_status').val('UPLOAD');
    }

    function select_apk_control(obj) {
        if(obj.value == '1'){
            click_apk_upload();
        }else{
            click_apk_fill_in();
        }
    }

    $(function() {

        $('#game_apk_upload').uploadify({
            'multi'    : true,
            'swf'      : '/game_center/assets/uploadify/uploadify.swf',
            'uploader' : '/game_center/util/upload?type=apk',
            'height' : 34,
            'width'  : 70,
            'onUploadSuccess' : function(file, data, response) {
                var data = $.parseJSON(data);
                var FILE_URL = data.localUrl;
                $.get("/game_center/util/aapt?apk="+FILE_URL, function(data){
                    // alert(data);
                    var data = $.parseJSON(data);
                    $('#package_info').html(data.package);
                    $('#version_info').html(data.version);
                    $('#size_info').html(data.size);
                    $('#apk_info').css('display', 'block');
                    $('#cdn_info').html('上传成功');

                    $('#package').val(data.package);
                    $('#version').val(data.version);
                    $('#size').val(data.size);
                    $('#apk_id').val(data.id);

                    uploadCDN(data.id, 'apk', FILE_URL);
                });
            }
        });

        $('#game_apk_upload-queue').addClass('gh-plugins-queue');
        $('#game_apk_upload-queue-item').addClass('gh-plugins-queue-item');
    });

</script>
