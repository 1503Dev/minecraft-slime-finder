<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once './BedrockSlimeFinder.php';
    require_once './JavaSlimeFinder.php';

    $edition = $_POST['edition'] ?? null;
    $seed = $_POST['seed'] ?? null;
    $x = $_POST['x'] ?? null;
    $z = $_POST['z'] ?? null;

    $x = (int)$x;
    $z = (int)$z;

    if ($edition == 'b') {
        echo BedrockSlimeFinder::isSlimeChunk($x, $z) == 1 ? "true" : "false";
        echo "\n";
        echo BedrockSlimeFinder::getSlimeRandomSeed($x, $z);
    } else {
        if (!extension_loaded('gmp') && !extension_loaded('php_gmp') && !is_callable('gmp_init')) {
            exit("false\nYou need to enable PHP gmp extension to continue");
        }
        if ($seed == '' || $seed == null) {
            exit("false\nInvalid seed");
        }
        echo JavaSlimeFinder::isSlimeChunk($seed, $x, $z) == 1 ? "true" : "false";
        echo "\n";
        echo JavaSlimeFinder::getSlimeRandomSeed($seed, $x, $z);
    }

    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minecraft Slime Finder</title>
    <script src="https://static.1503dev.top/ajax/jquery/3.7.1/jquery.min.js"></script>
    <style>
        .container {
            display: flex;
            gap: 0;
            justify-content: center;
        }

        .left,
        .right {
            padding: 20px;
            margin: 0;
        }

        .infoSeed {
            display: none;
        }
    </style>
</head>

<body>
    <center>
        <h2>Minecraft Slime Finder</h2>
        <a href="https://github.com/1503Dev/minecraft-slime-finder" target="_blank">github.com/1503Dev/minecraft-slime-finder</a>
        <div class="container">
            <div class="left">
                <h3>Edition</h3>
                <select class="edition">
                    <option value="b">Bedrock</option>
                    <option value="j">Java</option>
                </select>
                <h3>Chunk X</h3>
                <input type="number" class="chunkX">
                <h3>Chunk Z</h3>
                <input type="number" class="chunkZ">
            </div>
            <div class="right">
                <h3>Seed</h3>
                <input type="number" class="seed" disabled>
                <h3>World X</h3>
                <input type="number" class="worldX">
                <h3>World Z</h3>
                <input type="number" class="worldZ">
            </div>
        </div>
        <h3>Is Slime Chunk: <span class="result"></span></h3>
        <font color="red" class="infoSeed">You need to input a seed to check slime chunk in Java Edition</font>
    </center>
    <script>
        const NO = '<font color="red">NO</font>'
        const YES = '<font color="green">YES</font>'

        const chunkXInput = document.querySelector('.chunkX');
        const chunkZInput = document.querySelector('.chunkZ');
        const worldXInput = document.querySelector('.worldX');
        const worldZInput = document.querySelector('.worldZ');
        const seedInput = document.querySelector('.seed'); 
        const editionSelect = document.querySelector('.edition');

        const resultSpan = document.querySelector('.result');
        const infoSeed = document.querySelector('.infoSeed');

        chunkXInput.addEventListener('input', chunkToWorld);
        chunkZInput.addEventListener('input', chunkToWorld);
        worldXInput.addEventListener('input', worldToChunk);
        worldZInput.addEventListener('input', worldToChunk);
        seedInput.addEventListener('input', checkSlimeChunk);
        editionSelect.addEventListener('change', () => {
            if (editionSelect.value == 'b') {
                seedInput.setAttribute('disabled', 'true');
            } else {
                seedInput.removeAttribute('disabled');
            }
            checkSlimeChunk();
        });

        function worldToChunk() {
            let oX = worldXInput.value;
            let oZ = worldZInput.value;
            if (oX > 30000000) {
                oX = 30000000;
                worldXInput.value = 30000000
            } else if (oX < -30000000) {
                oX = -30000000;
                worldXInput.value = -30000000
            }
            if (oZ > 30000000) {
                oZ = 30000000;
                worldZInput.value = 30000000
            } else if (oZ < -30000000) {
                oZ = -30000000; 
                worldZInput.value = -30000000
            }

            const x = Math.floor(oX / 16);
            const z = Math.floor(oZ / 16);
            chunkXInput.value = x;
            chunkZInput.value = z;
            checkSlimeChunk();
        }
        function chunkToWorld() {
            let oX = chunkXInput.value;
            let oZ = chunkZInput.value;
             if (oX > 1875000) {
                oX = 1875000;
                chunkXInput.value = 1875000
            } else if (oX < -1875000) {
                oX = -1875000;
                chunkXInput.value = -1875000
            }
            if (oZ > 1875000) {
                oZ = 1875000;
                chunkZInput.value = 1875000
            } else if (oZ < -1875000) {
                oZ = -1875000;
                chunkZInput.value = -1875000
            }

            const x = oX * 16;
            const z = oZ * 16;
            worldXInput.value = x;
            worldZInput.value = z;
            checkSlimeChunk();
        }

        function checkSlimeChunk() {
            const edition = editionSelect.value;
            const chunkX = parseInt(chunkXInput.value);
            const chunkZ = parseInt(chunkZInput.value);
            const seed = seedInput.value;
            const seedValid = !isNaN(parseInt(seed))

            if (edition == 'b') {
                infoSeed.style.display = 'none';
            } else {
                if (!seedValid) {
                    infoSeed.style.display = 'block';
                } else {
                    infoSeed.style.display = 'none';
                }
            }

            if (isNaN(chunkX) || isNaN(chunkZ)) {
                resultSpan.innerHTML = NO;
                return;
            }
            $.post('./index.php', {
                edition: edition,
                seed: seed,
                x: chunkX,
                z: chunkZ
            }, (data)=>{
                data = data.split('\n')
                const isSlimeChunk = data[0] == "true" ? true : false
                resultSpan.innerHTML = (isSlimeChunk ? YES : NO) + ': ' + data[1];
            })
        }

        checkSlimeChunk();
    </script>
</body>

</html>