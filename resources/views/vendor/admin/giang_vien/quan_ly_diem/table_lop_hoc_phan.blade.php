<script>
    $(function () {
        $('#dotDangKy').change(function(e) {
            var selected = $( ".id_dot_dang_ky option:selected" ).val();
            $.ajax({
                type:'GET',
                url:'/admin/teacher/danh-sach-quan-ly-diem/'+selected,
                data:{_token: "{{ csrf_token() }}"
                },
                success: function( msg ) {
                    $('.grid-subject-register').hide();
                    $('.timetable-teacher').hide();
                    $('.gridTimeRegister').html(msg);
                }
            });
        });
    });
</script>
<div class="gridTimeRegister">
</div>
<div class="time-table ">
</div>
