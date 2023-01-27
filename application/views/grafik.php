<script type="text/javascript" src="<?php echo base_url(); ?>asset/admin/plugins/jQuery/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#container').highcharts({
            data: {
                table: 'datatable'
            },
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: ''
                }
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                        'Ada ' + this.point.y + ' Orang';
                }
            }
        });
    });
</script>

<style>
    #chat-box{
        width: 211.5%;
        justify-content: center;
        margin-left: -10px;
    }
    

    @media only screen and (max-width: 576px){
        #chat-box{
        width: 300px;
    }
    }
</style>

        <div class="box-body chat" id="chat-box">
        <div id="container"></div>
        <table id="datatable" style='display:none'>
            <thead>
                <tr>
                    <th></th>
                    <th>Jumlah Kunjungan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>10</td>
                    <td>20</td>
                </tr>
                <tr>
                    <td>10.5</td>
                    <td>15</td>
                </tr>
                <tr>
                    <td>11</td>
                    <td>30</td>
                </tr>
                <tr>
                    <td>11.5</td>
                    <td>25</td>
                </tr>
                <tr>
                    <td>12</td>
                    <td>25</td>
                </tr>
            </tbody>
        </table>
</div>  <!-- /.chat -->
<!-- </div>  /.box (chat box) -->

