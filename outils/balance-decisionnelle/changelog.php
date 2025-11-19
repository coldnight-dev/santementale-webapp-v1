<?php

function parse_md($text) {
    $lines = preg_split('/\r?\n/', $text);
    $html = '';
    $in_list = false;
    $in_pre = false;

    foreach ($lines as $line) {
        // Replace bold in the line
        $line = preg_replace('/\*\*(.*?)\*\*/', '<strong class="font-bold">$1</strong>', $line);

        $trimmed = ltrim($line);
        $indent = strlen($line) - strlen($trimmed);

        if (empty($trimmed)) {
            continue; // Skip empty lines for cleaner output
        }

        // Close blocks if starting a new type
        if (strpos($trimmed, '# ') !== 0 && strpos($trimmed, '## ') !== 0 && strpos($trimmed, '### ') !== 0 && $trimmed !== '---' && strpos($trimmed, '- ') !== 0 && $indent < 4) {
            if ($in_list) {
                $html .= '</ul>';
                $in_list = false;
            }
            if ($in_pre) {
                $html .= '</pre>';
                $in_pre = false;
            }
        }

        if (strpos($trimmed, '# ') === 0) {
            $html .= '<h1 class="text-3xl font-bold mt-6 mb-4 text-zinc-50">' . trim(substr($trimmed, 1)) . '</h1>';
        } elseif (strpos($trimmed, '## ') === 0) {
            $html .= '<h2 class="text-2xl font-semibold mt-5 mb-3 text-zinc-100">' . trim(substr($trimmed, 2)) . '</h2>';
        } elseif (strpos($trimmed, '### ') === 0) {
            $html .= '<h3 class="text-xl font-medium mt-4 mb-2 text-zinc-200">' . trim(substr($trimmed, 3)) . '</h3>';
        } elseif ($trimmed === '---') {
            $html .= '<hr class="my-4 border-zinc-700">';
        } elseif (strpos($trimmed, '- ') === 0) {
            if (!$in_list) {
                $html .= '<ul class="list-disc pl-6 mb-4 text-zinc-300">';
                $in_list = true;
            }
            $html .= '<li>' . trim(substr($trimmed, 1)) . '</li>';
        } elseif ($indent >= 4 || strpos($trimmed, '├──') === 0 || strpos($trimmed, '│') === 0 || strpos($trimmed, '/') === 0) { // Handle indented code or tree structures
            if (!$in_pre) {
                $html .= '<pre class="bg-zinc-800 p-4 rounded mb-4 overflow-x-auto text-zinc-400 font-mono text-sm">';
                $in_pre = true;
            }
            $html .= htmlspecialchars($line) . "\n";
        } else {
            $html .= '<p class="mb-4 text-zinc-300">' . $line . '</p>';
        }
    }

    // Close any open blocks at the end
    if ($in_list) {
        $html .= '</ul>';
    }
    if ($in_pre) {
        $html .= '</pre>';
    }

    return $html;
}

$files = glob('*.changelog');
if (empty($files)) {
    $content = '<p class="text-zinc-400">Aucun fichier .changelog trouvé dans le répertoire courant.</p>';
} else {
    $content = '';
    foreach ($files as $file) {
        $file_content = file_get_contents($file);
        $parsed = parse_md($file_content);
        $content .= '<div class="mb-12 border border-zinc-700 rounded-lg p-6 bg-zinc-800/50">';
        $content .= '<h2 class="text-xl font-bold mb-4 text-zinc-100">Fichier: ' . htmlspecialchars($file) . '</h2>';
        $content .= $parsed;
        $content .= '</div>';
    }
}

?>
<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parseur de Changelogs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles to enhance dark theme zinc palette */
        body {
            background-color: #18181b; /* zinc-900 */
            color: #d4d4d8; /* zinc-300 */
        }
        h1, h2, h3 {
            color: #f4f4f5; /* zinc-100 */
        }
        a, button {
            transition: all 0.2s ease;
        }
    </style>
</head>
<body class="min-h-screen p-6 relative">
    <button onclick="history.back()" class="fixed top-4 left-4 bg-zinc-700 hover:bg-zinc-600 text-zinc-100 px-4 py-2 rounded-md font-medium shadow-md">Retour</button>
    <div class="container mx-auto max-w-4xl mt-16">
        <h1 class="text-4xl font-bold mb-8 text-center text-zinc-50">Changelogs</h1>
        <?php echo $content; ?>
    </div>
</body>
</html>
