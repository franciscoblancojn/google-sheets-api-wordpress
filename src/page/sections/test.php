<?php
$CONFIG = get_option(GOSHAP_CONFIG, []);
$connections = $CONFIG['connections'] ?? [];
?>
<p for="APP_NAME">
    Se enviar una Fila con los datos : ["Fila 1","Fila 2","Fila 3","Fila 4","Fila 5"]
</p>

<?php if (!empty($connections)): ?>
    <label for="goshap_select">Seleccionar conexión:</label>
    <select id="goshap_select">
        <?php foreach ($connections as $conn): ?>
            <option value="<?= esc_attr($conn['KEY'] ?? '') ?>">
                Conexion <?= esc_html($conn['KEY'] ?? 'Sin KEY') ?>
            </option>
        <?php endforeach; ?>
    </select>
<?php else: ?>
    <p style="color:red;">⚠️ No hay conexiones configuradas</p>
<?php endif; ?>

<br><br>

<button
    id="<?= GOSHAP_KEY ?>_btnSend"
    class="button primary"
    onclick="onSendTest()">
    Enviar Row
</button>

<div id="<?= GOSHAP_KEY ?>_loader" style="display:none; margin-top:10px;">
    ⏳ Enviando...
</div>

<pre id="<?= GOSHAP_KEY ?>_responseBox" style="margin-top:10px; background:#f6f7f7; padding:10px;"></pre>

<script>
    const btn = document.getElementById('<?= GOSHAP_KEY ?>_btnSend');
    const loader = document.getElementById('<?= GOSHAP_KEY ?>_loader');
    const responseBox = document.getElementById('<?= GOSHAP_KEY ?>_responseBox');
    const select = document.getElementById('goshap_select');

    const onSendTest = async () => {
        const key = select?.value;

        if (!key) {
            responseBox.textContent = '❌ Debes seleccionar una conexión';
            return;
        }
        // UI estado loading
        btn.disabled = true;
        loader.style.display = 'block';
        responseBox.textContent = '';

        try {
            const res = await fetch(`/wp-json/<?= GOSHAP_KEY ?>/send-rows?k=${key}`, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify([
                    ["Fila 1", "Fila 2", "Fila 3", "Fila 4", "Fila 5"]
                ])
            });

            const data = await res.json();

            // Mostrar respuesta bonita
            responseBox.textContent = JSON.stringify(data, null, 2);

        } catch (error) {
            responseBox.textContent = '❌ Error: ' + error.message;
        } finally {
            // Reset UI
            btn.disabled = false;
            loader.style.display = 'none';
        }
    }
</script>
<?php
