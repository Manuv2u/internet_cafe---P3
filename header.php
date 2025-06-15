


<header>
    <div class="logo-header">
        <img src="https://cdn-icons-png.flaticon.com/512/888/888879.png" alt="System Logo">
        <h1>Internet Cafe Shop</h1>
    </div>
    <nav>
        <a href="landing.html" title="Home"><i class="fa fa-home"></i> Home</a>
    </nav>
</header>

<style>
    * {
        box-sizing: border-box;
    }

    header {
        width: 100%;
        height: auto;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 100;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 24px;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .logo-header {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .logo-header img {
        width: 36px;
        height: 36px;
    }

    .logo-header h1 {
        font-size: 20px;
        font-weight: bold;
        color: #2563eb;
        margin: 0;
    }

    header nav a {
        text-decoration: none;
        color: white;
        font-weight: 500;
        background: #2563eb;
        padding: 8px 14px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
    }

    header nav a:hover {
        background: #1e40af;
    }

    /* Optional: Add top padding to the body so content doesn't hide behind header */
    body {
        padding-top: 70px;
    }
</style>
