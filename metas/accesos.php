<div>
    <input type="checkbox" id="wp_subsk_enable_access" onchange="enable_access">
    <label for="wp_subsk_enable_access">Habilitar</label>
</div>
<div id="wp_subsk_content_access">

</div>
<script>
    function enable_access() {
        let content = document.querySelector('#wp_subsk_content_access');
        alert('hola')
    }
</script>