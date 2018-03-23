
<div class="modal fade" id="DescModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                 <h3 class="modal-title">Thông tin chi tiết về tài liệu</h3>
 
            </div>
            <div class="modal-body">
                 <table class="table table-striped table-bordered">
                    <tr>
                        <td width="30%">Tên sách</td>
                        <td><span id="txt-bookname"></span></td>
                    </tr>
                    <tr>
                        <td>Tên môn học</td>
                        <td><span id="txt-subj_name"></span></td>
                    </tr>
                    <tr>
                        <td>Mã môn học</td>
                        <td><span id="txt-subj_code"></span></td>
                    </tr>
                    <tr>
                        <td>Mã bộ môn</td>
                        <td><span id="txt-mabomon"></span></td>
                    </tr>
                    <tr>
                        <td>Tên bộ môn</td>
                        <td><span id="txt-tenbomon"></span></td>
                    </tr>
                    <tr>
                        <td>Tác giả</td>
                        <td><span id="txt-author"></span></td>
                    </tr>
                    <tr>
                        <td>Năm xuất bản</td>
                        <td><span id="txt-publishdate"></span></td>
                    </tr>
                    <tr>
                        <td>Nơi xb/ Nhà xb</td>
                        <td><span id="txt-publisher"></span></td>
                    </tr>
                    <tr>
                        <td>Ghi chú tài liệu</td>
                        <td><span id="txt-document_note"></span></td>
                    </tr>
                    <tr>
                        <td>Trạng thái</td>
                        <td><span id="txt-status"></span></td>
                    </tr>
                    <tr>
                        <td>Nơi lưu trữ</td>
                        <td><span id="txt-storageat"></span></td>
                    </tr>
                 </table>
                <div class="upload-form">
                    <span class="control-fileupload">
                        <label for="file">Choose a file :</label>
                        <input type="file" id="file">
                        <p class="status"></p><hr>
                        <button class="btn btn-primary btn-sm" id="up-file-btn">Tải lên</button>
                    </span>
                </div> 
            </div>
            <div class="modal-footer">
                <?php if ($logged) { ?>
                <a id="up-file-link" class="btn btn-default" href="" target="_blank">Tải lên file pdf</a>
                <?php } ?>
                <a id="view-file-link" class="btn btn-default" href="" target="_blank">Xem file pdf</a>
                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
