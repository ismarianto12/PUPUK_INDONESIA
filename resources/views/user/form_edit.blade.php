<div class="ket"></div>
<form class="simpan form-horizontal" method="POST" action="">


    <label class="col-md-2">Fulll Name</label>
    <div class="col-md-4">
        <input name="fullname" value="{{ $fullname }}" class="form-control" type="text" />
    </div>

    <label class="col-md-2">User Name</label>
    <div class="col-md-4">
        <input name="fullname" value="{{ $username }}" class="form-control" type="text" />
    </div>
    <label class="col-md-2">Email</label>
    <div class="col-md-4">
        <input name="email" value="{{ $email }}" class="form-control" type="email" />
    </div>
    <label class="col-md-2">password</label>
    <div class="col-md-4">
        <input name="password" class="form-control" type="password" />
    </div>
    <label class="col-md-2">Role </label>
    <div class="col-md-4">
        <input name="role_id" class="form-control" type="text" />
    </div>


    <div class="card-action">
        <div class="row">
            <div class="col-md-12">
                <input class="btn btn-success" type="submit" value="Simpan">
                <button class="btn btn-danger" type="reset">Batal</button>
            </div>
        </div>
    </div>
</form>
</div>



<script type="text/javascript">
    $(function() {
        $('.simpan').on('submit', function(e) {
            e.preventDefault();
            // alert('asa');
            $.ajax({
                url: "{{ route('user.update', $id) }}",
                method: "PUT",
                data: $(this).serialize(),
                chace: false,
                async: false,
                success: function(data) {
                    $('#datatable').DataTable().ajax.reload();
                    $('#formmodal').modal('hide');
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Data berhasil di simpan',
                        showConfirmButton: false,
                        timer: 1500
                    })
                },
                error: function(data) {
                    var div = $('#container');
                    setInterval(function() {
                        var pos = div.scrollTop();
                        div.scrollTop(pos + 2);
                    }, 10)
                    err = '';
                    respon = data.responseJSON;
                    $.each(respon.errors, function(index, value) {
                        err += "<li>" + value + "</li>";
                    });
                    $('.ket').html(
                        "<div role='alert' class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><strong>Perahtian donk!</strong> " +
                        respon.message + "<ol class='pl-3 m-0'>" + err + "</ol></div>");


                }
            })
        });
    });
</script>
