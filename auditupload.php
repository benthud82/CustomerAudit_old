<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
    ?>
    <head>
        <title>Mass Audit Upload</title>
        <?php include_once 'headerincludes.php'; ?>
    </head>

    <body style="">
        <!--include horz nav php file-->
        <?php include_once 'horizontalnav.php'; ?>
        <!--include vert nav php file-->
        <?php include_once 'verticalnav.php'; ?>


        <section id="content"> 
            <section class="main padder"> 

                <div class="" style="padding-bottom: 25px; padding-top: 20px;">
                    <div id="salesplansummary" class="panel-body" style="background: #efefef">
                        <div class="col-lg-4">
                            <form action="" method="post" enctype="multipart/form-data" target="_blank" id="uploadform" style="display: -webkit-inline-box;">
                                Select File to Upload:
                                <input type="file" name="fileToUpload" id="fileToUpload">
                                <input type="button" class="btn btn-success" id="auditupload_submit" name="" value="Upload" />
                            </form>
                        </div>
                    </div>
                </div>

            </section>
        </section>


        <script>
            $("body").tooltip({selector: '[data-toggle="tooltip"]'});
            $('body').tooltip({
                selector: '[rel=tooltip]'
            });
            $("#modules").addClass('active'); //add active strip to audit on vertical nav


            //ajax logic to parse the file and insert into audit table customeraction_asgntasks
            $(document).on("click", "#auditupload_submit", function (event) {
                debugger;
                event.preventDefault();
                var file = $('#fileToUpload').get(0).files[0];
                var formData = new FormData();
                formData.append('file', file);

//                var upload_custtype = $('#upload_custtype').val();
//                var upload_custid = $('#upload_custid').val();
//                formData.append('upload_custtype', upload_custtype);
//                formData.append('upload_custid', upload_custid);


                //ajax for uload document panel
                $.ajax({
                    url: 'globaldata/upload_massaudit.php',
                    type: 'POST',
                    data: formData,
                    processData: false, // tell jQuery not to process the data
                    contentType: false, // tell jQuery not to set contentType
                    success: function (data) {
                        console.log(data);
                        loaduploadmodule();
                        alert(data);
                    }
                });
            });



        </script>

    </body>
</html>

