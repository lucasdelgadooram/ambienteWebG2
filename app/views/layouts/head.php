<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Paluse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- estilo general-->
      <link rel="stylesheet" href="/frontend-paluse/public/css/general.css">

    <link rel="stylesheet" href="/frontend-paluse/public/css/header.css">

    <link rel="stylesheet" href="/frontend-paluse/public/css/footer.css">


    <!-- estilos específicos-->
    <?php
    if(isset($data['css'])){
        foreach($data['css'] as $css){
            
            echo '<link rel="stylesheet" href="'.BASE_URL.'/css/'.$css.'">';
        }
    }
    ?>
</head>

<body>