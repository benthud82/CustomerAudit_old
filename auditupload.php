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

                    <div class="row" style="padding-bottom: 25px;"> 

                        <div class="col-md-3 col-sm-3 col-xs-12 col-lg-2 col-xl-2 text-center">
                            <label>Group Type:</label>
                            <select name = 'grouptype' class = "form-control" id = 'grouptype'>
                                <option value="SALESPLAN">Sales Plan</option>
                                <option value="SHIPTO">Ship To</option>
                                <option value="BILLTO">Bill To</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 col-lg-2 col-xl-2 text-center">
                            <label>Group ID:</label>
                            <input type="text" name="groupid" id="groupid" class="form-control" placeholder="salesplan name, bill to #, or ship to #" tabindex="3"/>
                        </div>
                    </div>


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


                <div id="modal_uploadstatus" class="modal fade " role="dialog">
                    <div class="modal-dialog modal-lg">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Upload Status</h4>
                            </div>

                            <div class="modal-body">
                                <div id="ajaxresult"></div>
                            </div>

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
//                debugger;
                $('#modal_uploadstatus').modal('toggle');

                event.preventDefault();
                var file = $('#fileToUpload').get(0).files[0];
                var formData = new FormData();
                formData.append('file', file);
                var grouptype = $('#grouptype').val();
                var groupid = $('#groupid').val();
                formData.append('grouptype', grouptype);
                formData.append('groupid', groupid);
                //ajax for upload document panel
                $.ajax({
                    url: 'globaldata/upload_massaudit.php',
                    type: 'POST',
                    data: formData,
                    processData: false, // tell jQuery not to process the data
                    contentType: false, // tell jQuery not to set contentType
                    success: function (data) {
                        $("#ajaxresult").html(data);
                        //clear the file from upload
                        document.getElementById('fileToUpload').value = null;
                    }
                });
            });



        </script>

    </body>
</html>


