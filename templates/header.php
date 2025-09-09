<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <title>Orçamento Madeira - Cadastro</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- FontAwesome para ícones -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <!-- CSS customizado -->
  <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid navbar-inner">
      <div class="d-flex align-items-center w-100 justify-content-between">
        <a href="index.php" class="navbar-brand">
          <img src="assets/images/logo/ekowood.png" alt="Logo Orçamento Madeira" class="logo-img" />
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarContent"
          aria-controls="navbarContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <div class="collapse navbar-collapse justify-content-center" id="navbarContent">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link <?= ($_SERVER['SCRIPT_NAME'] === '/index.php') ? 'active' : '' ?>" href="index.php"><i class="fas fa-user"></i> Cadastro</a></li>
          <li class="nav-item"><a class="nav-link <?= ($_SERVER['SCRIPT_NAME'] === '/produtos.php') ? 'active' : '' ?>" href="produtos.php"><i class="fas fa-cubes"></i> Produtos</a></li>
          <li class="nav-item"><a class="nav-link <?= ($_SERVER['SCRIPT_NAME'] === '/lista_produtos.php') ? 'active' : '' ?>" href="lista_produtos.php"><i class="fas fa-list-ul"></i> Lista</a></li>
          <li class="nav-item"><a class="nav-link <?= ($_SERVER['SCRIPT_NAME'] === '/resumo.php') ? 'active' : '' ?>" href="resumo.php"><i class="fas fa-clipboard-list"></i> Resumo</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <main class="container">
