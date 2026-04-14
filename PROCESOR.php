<?php
$izdelekIme = $_GET['ImeIzdelek'] ?? null;
$ceneIzdelkov = [
    'Domači dres 2024/25' => 99.99,
    'Gostujoči dres' => 94.99,
    'Tretji dres' => 92.99,
    'Otroški dres' => 69.99,
    'Trening majica' => 49.99,
    'Jakna' => 79.99,
    'Trening hlače' => 44.99,
    'Pulover' => 59.99,
    'Barça šal' => 19.99,
    'Nogometna žoga' => 39.99,
    'Barça nahrbtnik' => 34.99,
    'Barça skodelica' => 14.99

];

$sporocilo = '';
$napaka = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imeKupca = trim($_POST['ime_kupca'] ?? '');
    $emailKupca = trim($_POST['email_kupca'] ?? '');
    $telefonKupca = trim($_POST['telefon_kupca'] ?? '');
    $naslovKupca = trim($_POST['naslov_kupca'] ?? '');
    $mestoKupca = trim($_POST['mesto_kupca'] ?? '');
    $postaStevilka = trim($_POST['posta_stevilka'] ?? '');
    $izdelekImePost = trim($_POST['izdelek_ime'] ?? '');
    $kolicina = (int)($_POST['kolicina'] ?? 1);
    $opombe = trim($_POST['opombe'] ?? '');

    if ($imeKupca === '' || $emailKupca === '' || $telefonKupca === '' || $naslovKupca === '' || $mestoKupca === '' || $postaStevilka === '' || $izdelekImePost === '') {
        $napaka = 'Prosimo, izpolnite vsa obvezna polja.';
        $izdelekIme = $izdelekImePost;
    } elseif (!filter_var($emailKupca, FILTER_VALIDATE_EMAIL)) {
        $napaka = 'E-poštni naslov ni pravilen.';
        $izdelekIme = $izdelekImePost;
    } elseif ($kolicina < 1) {
        $napaka = 'Količina mora biti vsaj 1.';
        $izdelekIme = $izdelekImePost;
    } else {
        $cenaIzdelka = $ceneIzdelkov[$izdelekImePost] ?? 59.99;
        $skupnaCena = $cenaIzdelka * $kolicina;

        $za = 'test@test.si';
        $zadeva = 'Novo naročilo - FC Barcelona trgovina';

        $vsebina = "Prejeto je novo naročilo.\n\n";
        $vsebina .= "Ime in priimek kupca: " . $imeKupca . "\n";
        $vsebina .= "E-pošta kupca: " . $emailKupca . "\n";
        $vsebina .= "Telefon kupca: " . $telefonKupca . "\n";
        $vsebina .= "Naslov kupca: " . $naslovKupca . "\n";
        $vsebina .= "Mesto kupca: " . $mestoKupca . "\n";
        $vsebina .= "Poštna številka: " . $postaStevilka . "\n";
        $vsebina .= "Izdelek: " . $izdelekImePost . "\n";
        $vsebina .= "Količina: " . $kolicina . "\n";
        $vsebina .= "Cena na kos: " . number_format($cenaIzdelka, 2, ',', '.') . " €\n";
        $vsebina .= "Skupna cena: " . number_format($skupnaCena, 2, ',', '.') . " €\n";
        $vsebina .= "Opombe: " . ($opombe !== '' ? $opombe : 'Brez opomb') . "\n";

        $glave = "From: trgovina@fcbarcelona.local\r\n";
        $glave .= "Reply-To: " . $emailKupca . "\r\n";
        $glave .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($za, $zadeva, $vsebina, $glave)) {
            $sporocilo = 'Naročilo je bilo uspešno poslano.';
        } else {
            $napaka = 'Pri pošiljanju naročila je prišlo do napake.';
        }

        $izdelekIme = $izdelekImePost;
    }
}

$cenaIzdelkaZaPrikaz = 0.00;
if ($izdelekIme !== null) {
    $cenaIzdelkaZaPrikaz = $ceneIzdelkov[$izdelekIme] ?? "NAPAKA";
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>FC Barcelona – Spletna trgovina</title>
    <link rel="stylesheet" href="style.css">
</head>
<header class="header">
    <div class="header-content">
        <img src="slike/logo.webp" alt="FC Barcelona logo" class="logo">

        <div class="header-text">
            <h1>FC Barcelona</h1>
            <p>Spletna trgovina</p>
        </div>
    </div>
</header>


<nav>
    <ul>
        <li><a href="index.html">Domov</a></li>
        <li><a href="trgovina.html">Trgovina</a></li>
        <li><a href="onas.html">O nas</a></li>
        <li><a href="kontakt.html">Kontakt</a></li>
    </ul>
</nav>

<body style="margin:0; padding:0; background:#f4f7fb; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">


<div style="max-width:1000px; margin:40px auto; padding:0 20px;">

<?php if ($sporocilo !== ''): ?>
    <div style="max-width:720px; margin:0 auto 30px auto; padding:22px 24px; border-radius:18px; background:linear-gradient(135deg, #ecfdf3, #dff7ea); border:1px solid #a7e0bd; box-shadow:0 10px 30px rgba(16, 185, 129, 0.12); color:#065f46; display:flex; align-items:center; gap:16px;">
        <div style="width:54px; height:54px; min-width:54px; border-radius:14px; background:#10b981; display:flex; align-items:center; justify-content:center; box-shadow:0 6px 18px rgba(16, 185, 129, 0.25);">
            <span style="color:#ffffff; font-size:26px; font-weight:bold; line-height:1;">✓</span>
        </div>
        <div>
            <div style="font-size:18px; font-weight:700; margin-bottom:6px;">Uspešno poslano</div>
            <div style="font-size:14px; line-height:1.5;"><?php echo htmlspecialchars($sporocilo, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
    </div>
<?php endif; ?>

<?php if ($napaka !== ''): ?>
    <div style="max-width:720px; margin:0 auto 30px auto; padding:22px 24px; border-radius:18px; background:linear-gradient(135deg, #fff5f5, #ffeaea); border:1px solid #f5c2c7; box-shadow:0 10px 30px rgba(220, 53, 69, 0.12); color:#842029; display:flex; align-items:center; gap:16px;">
        <div style="width:54px; height:54px; min-width:54px; border-radius:14px; background:#dc3545; display:flex; align-items:center; justify-content:center; box-shadow:0 6px 18px rgba(220, 53, 69, 0.25);">
            <span style="color:#ffffff; font-size:28px; font-weight:bold; line-height:1;">!</span>
        </div>
        <div>
            <div style="font-size:18px; font-weight:700; margin-bottom:6px;">Prišlo je do napake</div>
            <div style="font-size:14px; color:#a94442; line-height:1.5;"><?php echo htmlspecialchars($napaka, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
    </div>
<?php endif; ?>

<?php if ($izdelekIme == null): ?>

    <div style="max-width:720px; margin:0 auto; padding:22px 24px; border-radius:18px; background:linear-gradient(135deg, #fff5f5, #ffeaea); border:1px solid #f5c2c7; box-shadow:0 10px 30px rgba(220, 53, 69, 0.12); color:#842029; display:flex; align-items:center; gap:16px;">
        <div style="width:54px; height:54px; min-width:54px; border-radius:14px; background:#dc3545; display:flex; align-items:center; justify-content:center; box-shadow:0 6px 18px rgba(220, 53, 69, 0.25);">
            <span style="color:#ffffff; font-size:28px; font-weight:bold; line-height:1;">!</span>
        </div>
        <div>
            <div style="font-size:18px; font-weight:700; margin-bottom:6px; letter-spacing:0.3px;">Prišlo je do napake</div>
            <div style="font-size:14px; color:#a94442; line-height:1.5;">
                Napaka pri inicializaciji sistema. Izdelek ni bil podan v URL parametru <strong>ImeIzdelek</strong>.
            </div>
        </div>
    </div>

<?php else: ?>

    <div style="max-width:720px; margin:0 auto; background:#ffffff; border-radius:24px; box-shadow:0 18px 45px rgba(0,0,0,0.08); overflow:hidden;">
        <div style="background:linear-gradient(135deg, #8b1538, #004d98); color:#ffffff; padding:28px 30px;">
            <h2 style="margin:0; font-size:28px;">Oddaja naročila</h2>
            <p style="margin:8px 0 0 0; font-size:15px; opacity:0.95;">Izpolnite spodnji obrazec za nakup izdelka.</p>
        </div>

        <form method="post" action="" style="padding:30px;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">

                <div style="grid-column:1 / -1;">
                    <label for="izdelek_ime" style="display:block; margin-bottom:8px; font-weight:700; font-size:14px;">Izdelek</label>
                    <input
                        type="text"
                        id="izdelek_ime"
                        name="izdelek_ime"
                        value="<?php echo htmlspecialchars($izdelekIme, ENT_QUOTES, 'UTF-8'); ?>"
                        readonly
                        style="width:100%; box-sizing:border-box; padding:14px 16px; border:1px solid #d1d5db; border-radius:14px; background:#f3f4f6; color:#374151; font-size:15px;"
                    >
                </div>

                <div>
                    <label for="ime_kupca" style="display:block; margin-bottom:8px; font-weight:700; font-size:14px;">Ime in priimek kupca</label>
                    <input
                        type="text"
                        id="ime_kupca"
                        name="ime_kupca"
                        required
                        value="<?php echo htmlspecialchars($_POST['ime_kupca'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        style="width:100%; box-sizing:border-box; padding:14px 16px; border:1px solid #d1d5db; border-radius:14px; background:#ffffff; font-size:15px;"
                    >
                </div>

                <div>
                    <label for="posta_kupca" style="display:block; margin-bottom:8px; font-weight:700; font-size:14px;">Pošta kupca</label>
                    <input
                        type="text"
                        id="posta_kupca"
                        name="posta_kupca"
                        required
                        value="<?php echo htmlspecialchars($_POST['posta_kupca'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        style="width:100%; box-sizing:border-box; padding:14px 16px; border:1px solid #d1d5db; border-radius:14px; background:#ffffff; font-size:15px;"
                    >
                </div>

                <div>
                    <label for="email_kupca" style="display:block; margin-bottom:8px; font-weight:700; font-size:14px;">E-pošta kupca</label>
                    <input
                        type="email"
                        id="email_kupca"
                        name="email_kupca"
                        required
                        value="<?php echo htmlspecialchars($_POST['email_kupca'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        style="width:100%; box-sizing:border-box; padding:14px 16px; border:1px solid #d1d5db; border-radius:14px; background:#ffffff; font-size:15px;"
                    >
                </div>

                <div>
                    <label for="telefon_kupca" style="display:block; margin-bottom:8px; font-weight:700; font-size:14px;">Telefon kupca</label>
                    <input
                        type="text"
                        id="telefon_kupca"
                        name="telefon_kupca"
                        required
                        value="<?php echo htmlspecialchars($_POST['telefon_kupca'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        style="width:100%; box-sizing:border-box; padding:14px 16px; border:1px solid #d1d5db; border-radius:14px; background:#ffffff; font-size:15px;"
                    >
                </div>

                <div style="grid-column:1 / -1;">
                    <label for="naslov_kupca" style="display:block; margin-bottom:8px; font-weight:700; font-size:14px;">Naslov kupca</label>
                    <input
                        type="text"
                        id="naslov_kupca"
                        name="naslov_kupca"
                        required
                        value="<?php echo htmlspecialchars($_POST['naslov_kupca'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        style="width:100%; box-sizing:border-box; padding:14px 16px; border:1px solid #d1d5db; border-radius:14px; background:#ffffff; font-size:15px;"
                    >
                </div>

                <div>
                    <label for="mesto_kupca" style="display:block; margin-bottom:8px; font-weight:700; font-size:14px;">Mesto</label>
                    <input
                        type="text"
                        id="mesto_kupca"
                        name="mesto_kupca"
                        required
                        value="<?php echo htmlspecialchars($_POST['mesto_kupca'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        style="width:100%; box-sizing:border-box; padding:14px 16px; border:1px solid #d1d5db; border-radius:14px; background:#ffffff; font-size:15px;"
                    >
                </div>

                <div>
                    <label for="posta_stevilka" style="display:block; margin-bottom:8px; font-weight:700; font-size:14px;">Poštna številka</label>
                    <input
                        type="text"
                        id="posta_stevilka"
                        name="posta_stevilka"
                        required
                        value="<?php echo htmlspecialchars($_POST['posta_stevilka'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        style="width:100%; box-sizing:border-box; padding:14px 16px; border:1px solid #d1d5db; border-radius:14px; background:#ffffff; font-size:15px;"
                    >
                </div>

                <div>
                    <label for="kolicina" style="display:block; margin-bottom:8px; font-weight:700; font-size:14px;">Količina</label>
                    <input
                        type="number"
                        id="kolicina"
                        name="kolicina"
                        min="1"
                        value="<?php echo htmlspecialchars($_POST['kolicina'] ?? '1', ENT_QUOTES, 'UTF-8'); ?>"
                        required
                        oninput="preracunajSkupaj()"
                        style="width:100%; box-sizing:border-box; padding:14px 16px; border:1px solid #d1d5db; border-radius:14px; background:#ffffff; font-size:15px;"
                    >
                </div>

                <div>
                    <label for="cena_izdelka" style="display:block; margin-bottom:8px; font-weight:700; font-size:14px;">Cena na kos</label>
                    <input
                        type="text"
                        id="cena_izdelka"
                        value="<?php echo number_format($cenaIzdelkaZaPrikaz, 2, ',', '.'); ?> €"
                        readonly
                        style="width:100%; box-sizing:border-box; padding:14px 16px; border:1px solid #d1d5db; border-radius:14px; background:#f3f4f6; color:#374151; font-size:15px;"
                    >
                </div>

                <div>
                    <label for="skupna_cena" style="display:block; margin-bottom:8px; font-weight:700; font-size:14px;">Skupna cena</label>
                    <input
                        type="text"
                        id="skupna_cena"
                        value="<?php
                            $zacetnaKolicina = (int)($_POST['kolicina'] ?? 1);
                            if ($zacetnaKolicina < 1) {
                                $zacetnaKolicina = 1;
                            }
                            echo number_format($cenaIzdelkaZaPrikaz * $zacetnaKolicina, 2, ',', '.');
                        ?> €"
                        readonly
                        style="width:100%; box-sizing:border-box; padding:14px 16px; border:1px solid #d1d5db; border-radius:14px; background:#eef6ff; color:#003366; font-size:15px; font-weight:bold;"
                    >
                </div>

                <div style="grid-column:1 / -1;">
                    <label for="opombe" style="display:block; margin-bottom:8px; font-weight:700; font-size:14px;">Opombe</label>
                    <textarea
                        id="opombe"
                        name="opombe"
                        rows="4"
                        style="width:100%; box-sizing:border-box; padding:14px 16px; border:1px solid #d1d5db; border-radius:14px; background:#ffffff; font-size:15px; resize:vertical;"
                    ><?php echo htmlspecialchars($_POST['opombe'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

            </div>

            <div style="margin-top:28px; padding:20px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:18px;">
                <div style="display:flex; justify-content:space-between; align-items:center; gap:20px; flex-wrap:wrap;">
                    <div>
                        <div style="font-size:14px; color:#6b7280; margin-bottom:6px;">Končni znesek za plačilo</div>
                        <div id="skupaj_veliko" style="font-size:28px; font-weight:800; color:#8b1538;">
                            <?php echo number_format($cenaIzdelkaZaPrikaz * $zacetnaKolicina, 2, ',', '.'); ?> €
                        </div>
                    </div>

                    <button
                        type="submit"
                        style="border:none; cursor:pointer; padding:16px 28px; border-radius:14px; background:linear-gradient(135deg, #8b1538, #004d98); color:#ffffff; font-size:16px; font-weight:bold; box-shadow:0 10px 20px rgba(0,77,152,0.22);"
                    >
                        Oddaj naročilo
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function preracunajSkupaj() {
            var cena = <?php echo json_encode((float)$cenaIzdelkaZaPrikaz); ?>;
            var kolicinaInput = document.getElementById('kolicina');
            var skupnaCenaInput = document.getElementById('skupna_cena');
            var skupajVeliko = document.getElementById('skupaj_veliko');

            var kolicina = parseInt(kolicinaInput.value || '1', 10);

            if (isNaN(kolicina) || kolicina < 1) {
                kolicina = 1;
            }

            var skupaj = cena * kolicina;

            var formatirano = skupaj.toFixed(2).replace('.', ',') + ' €';

            skupnaCenaInput.value = formatirano;
            skupajVeliko.innerHTML = formatirano;
        }

        preracunajSkupaj();
    </script>

<?php endif; ?>

</div>

</body>
</html>