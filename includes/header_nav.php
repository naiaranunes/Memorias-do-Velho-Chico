<header class="header">
  <div class="header-logo">
    <a href="/sitelendas/index.php">
      <img class="logo" src="/sitelendas/assets/images/logoponte.png" alt="logo site">
      <h1>Memórias do Vale</h1>
    </a>
  </div>

  <div class="header-search">
    <form method="GET" action="">
      <input
        type="text"
        name="q"
        placeholder="Buscar histórias"
        value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
      <button type="submit">Buscar</button>
    </form>
  </div>

  <div class="header-right">
    <a class="btn-story" href="/SITELENDAS/stories/create.php">Registrar</a>

    <span class="header-user">Olá, <?= htmlspecialchars($_SESSION['nome']) ?></span>

    <div class="header-menu">
      <button id="menuToggle" class="menu-toggle" aria-label="Abrir menu">☰</button>
    </div>
  </div>
</header>

<nav class="profile-menu" id="menu">
  <ul>
    <li><a href="/sitelendas/user/profile.php">Editar perfil</a></li>
    <li><a href="/sitelendas/stories/meusregistros.php">Meus Registros</a></li>
    <li><a href="/sitelendas/user/password.php">Alterar senha</a></li>
    <li><a href="/sitelendas/user/deleteconta.php">Deletar conta</a></li>
    <li><a href="/sitelendas/auth/logout.php">Sair</a></li>
  </ul>
</nav>