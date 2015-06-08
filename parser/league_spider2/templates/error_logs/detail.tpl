<div class="right_options">
    <button onclick="self.location.href = 'index.php?parser=league_spider2&logger&error_logs';">Zur&uuml;ck</button>
    <button onclick="self.location.href = 'index.php?parser=league_spider2&logger&error_logs&log_name={NAME}&delete';">L&ouml;schen</button>
</div>
<h1 class="site_title">League Spider 2.0 - Error-Log <span>> {DATE} - {TIME} Uhr</span></h1>

<table class="table" id="list_holder">
    <tbody>
    </tbody>
</table>

<script>
    function nl2br(str, is_xhtml){
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }
    
    $(document).ready(function(){
        array = {FILE};
        $.each(array, function(key, value){
            html = '<tr><td>'+key+'</td><td>'+nl2br(value)+'</td></tr>';
            $("#list_holder").find("tbody").append(html);
        });
    });
</script>