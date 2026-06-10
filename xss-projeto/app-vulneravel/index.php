<?php
$comentarios = [];
$arquivo = '/tmp/comentarios.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
    $comentario = $_POST['comentario'];
    file_put_contents($arquivo, $comentario . "\n", FILE_APPEND);
}

if (file_exists($arquivo)) {
    $comentarios = file($arquivo, FILE_IGNORE_NEW_LINES);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Blog Vulneravel - Demo XSS</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 40px auto; padding: 20px; }
        .comentario { background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px; }
        input[type=text] { width: 70%; padding: 8px; }
        button { padding: 8px 16px; background: #007bff; color: white; border: none; cursor: pointer; }
        h1 { color: #333; }
        .aviso { background: #fff3cd; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Blog de Comentarios</h1>
    <div class="aviso">⚠️ Esta aplicacao e intencionalmente vulneravel para fins educacionais.</div>

    <h2>Deixe seu comentario:</h2>
    <form method="POST">
        <input type="text" name="comentario" placeholder="Digite seu comentario aqui..." />
        <button type="submit">Enviar</button>
    </form>

    <h2>Comentarios:</h2>
    <?php foreach ($comentarios as $c): ?>
        <div class="comentario"><?= $c ?></div>
    <?php endforeach; ?>
</body>
</html>
