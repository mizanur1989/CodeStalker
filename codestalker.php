<?php
// CodeStalker v1.1 - Полная CLI Hacking Game
// Создатель: web-pentest

class CodeStalker {
    private $currentMission = 1;
    private $missions = [
        1 => ['name' => 'Сетевая разведка', 'command' => 'nmap', 'progress' => 20],
        2 => ['name' => 'Взлом админ-панели', 'command' => 'hydra', 'progress' => 25],
        3 => ['name' => 'SQL-инъекции', 'command' => 'sqlmap', 'progress' => 25],
        4 => ['name' => 'Социальная инженерия', 'command' => 'phish', 'progress' => 15],
        5 => ['name' => 'Расшифровка ransomware', 'command' => 'decrypt', 'progress' => 15]
    ];

    private $playerData = [
        'name' => 'GhostHacker',
        'rank' => 'Новичок',
        'progress' => 0,
        'missions_completed' => 0,
        'achievements' => [],
        'start_time' => 0
    ];

    private $saveFile = 'save.json';
    public function run() {
        $this->loadSave();
        $this->playerData['start_time'] = time();
        $this->showIntro();

        while (true) {
            $command = readline('codestalker$ ');
            $this->processCommand($command);
            $this->saveProgress();
        }
    }

    private function showIntro() {
        echo "\n";
        echo "╔══════════════════════════════════════════════════════════════════════╗\n";
        echo "║                    CODESTALKER v1.1 - FULL VERSION                    ║\n";
        echo "║                    ████ MEGA CORP BREACH ████                        ║\n";
        echo "╚══════════════════════════════════════════════════════════════════════╝\n\n";

        echo "[🚨 БРИФИНГ МИССИИ] Год 2025. MegaCorp подвергся кибератаке!\n";
        echo "💰 Украдено $5M, системы зашифрованы ransomware\n";
        echo "👤 Генеральный директор пропал, данные сотрудников скомпрометированы\n";
        echo "🎯 Твоя задача: проникнуть в сеть, найти виновных, восстановить данные!\n\n";

        echo "[📊 СТАТУС] Игрок: ". $this->playerData['name']. " | Ранг: ". $this->playerData['rank']. "\n";
        echo "[📈 ПРОГРЕСС] ". $this->playerData['progress']. "% | Миссии: ". $this->playerData['missions_completed']. "/5\n\n";

        $this->showCurrentMission();
        echo "[💡 ПОДСКАЗКА] Введи 'help' для списка команд или '". $this->missions[$this->currentMission]['command']. "' для текущей миссии\n\n";
    }

    private function showCurrentMission() {
        $mission = $this->missions[$this->currentMission];
        echo "[🎯 ТЕКУЩАЯ МИССИЯ ". $this->currentMission. "/5] ". $mission['name']. "\n";
        echo "[📋 ЦЕЛЬ] Используй команду: ". $mission['command']. "\n\n";
    }
    private function processCommand($command) {
        $cmd = strtolower(trim($command));

        switch (true) {
            case $cmd == 'help':
                $this->showHelp();
                break;
            case $cmd == 'whoami':
                $this->showPlayerInfo();
                break;
            case $cmd == 'status':
                $this->showStatus();
                break;
            case strpos($cmd, 'nmap')!== false:
                $this->handleMission(1, 'nmap');
                break;
            case strpos($cmd, 'hydra')!== false:
                $this->handleMission(2, 'hydra');
                break;
            case strpos($cmd, 'sqlmap')!== false:
                $this->handleMission(3, 'sqlmap');
                break;
            case strpos($cmd, 'phish')!== false:
                $this->handleMission(4, 'phish');
                break;
            case strpos($cmd, 'decrypt')!== false:
                $this->handleMission(5, 'decrypt');
                break;
            case $cmd == 'about':
            case $cmd == 'profile':
                $this->showAbout();
                break;
            case $cmd == 'exit':
            case $cmd == 'quit':
                echo "\n[👋 Сеанс завершен. Хорошей охоты!]\n";
                $this->saveProgress();
                exit(0);
            case $cmd == 'clear':
                system('clear');
                $this->showIntro();
                break;
            default:
                echo "[❌ ОШИБКА] Команда не распознана.\n";
                echo "[💡 ПОДСКАЗКА] Попробуй: help, whoami, или '". $this->missions[$this->currentMission]['command']. "'\n\n";
        }
    }

    private function handleMission($missionNum, $command) {
        if ($this->currentMission!= $missionNum) {
            echo "[⚠️ ПРЕДУПРЕЖДЕНИЕ] Сначала заверши миссию ". $this->currentMission. "!\n";
            echo "[💡 Текущая задача: ". $this->missions[$this->currentMission]['command']. "]\n\n";
            return;
        }

        $mission = $this->missions[$missionNum];
        echo "\n[🚀 ЗАПУСК МИССИИ ". $missionNum. "] ". $mission['name']. "\n";
        echo "══════════════════════════════════════════════════════════════════════\n";

        switch ($missionNum) {
            case 1:
                $this->simulateNmap();
                break;
            case 2:
                $this->simulateHydra();
                break;
            case 3:
                $this->simulateSqlmap();
                break;
            case 4:
                $this->simulatePhish();
                break;
            case 5:
                $this->simulateDecrypt();
                break;
        }

        $this->playerData['progress'] += $mission['progress'];
        $this->playerData['missions_completed']++;
        $this->currentMission++;

        $this->updateRank();
        $this->showAchievement($missionNum);

        if ($this->playerData['missions_completed'] >= 5) {
            $this->completeGame();
        } else {
            echo "\n[✅ МИССИЯ ". $missionNum. " ВЫПОЛНЕНА!]\n";
            echo "[📈 ПРОГРЕСС] ". $this->playerData['progress']. "% | Миссии: ". $this->playerData['missions_completed']. "/5\n\n";
            $this->showCurrentMission();
        }
    }
    private function updateRank() {
        $ranks = [
            0 => 'Новичок',
            20 => 'Хактивист', 
            45 => 'Пентестер',
            70 => 'Элитный хакер',
            100 => 'Кибер-бог'
        ];

        foreach ($ranks as $progress => $rank) {
            if ($this->playerData['progress'] >= $progress) {
                $this->playerData['rank'] = $rank;
            }
        }
    }

    private function showAchievement($missionNum) {
        $achievements = [
            1 => 'Разведчик сети',
            2 => 'Мастер паролей', 
            3 => 'Корпоративный шпион',
            4 => 'Мастер социальной инженерии',
            5 => 'Крипто-гений'
        ];

        $achievement = $achievements[$missionNum];
        $this->playerData['achievements'][] = $achievement;

        echo "[🏆 ДОСТИЖЕНИЕ РАЗБЛОКИРОВАНО] '". $achievement. "'\n";
        echo "[📈 ОБНОВЛЁННЫЙ РАНГ] ". $this->playerData['rank']. "\n\n";
    }

    private function progressBar($total, $width = 50, $color = 'green') {
        $colors = [
            'green' => "\033[32m",
            'yellow' => "\033[33m", 
            'red' => "\033[31m",
            'blue' => "\033[34m",
            'purple' => "\033[35m",
            'reset' => "\033[0m"
        ];

        for ($i = 0; $i <= $total; $i += 5) {
            $percent = $i;
            $filled = intval($width * $percent / 100);
            $bar = str_repeat('█', $filled). str_repeat('░', $width - $filled);

            $colorCode = $colors[$color]?? $colors['green'];
            $reset = $colors['reset'];

            echo "\r". $colorCode. "[ПРОГРЕСС] [". $bar. "] ". $percent. "%". $reset;
            ob_flush();
            flush();
            usleep(80000);
        }
        echo "\n". $reset;
    }

    private function simulateNmap() {
        echo "[🔍 СКАНИРОВАНИЕ СЕТИ 192.168.1.0/24]\n";
        $this->progressBar(100, 50, 'green');

        echo "\n[📡 ОБНАРУЖЕНЫ УЗЛЫ:]\n";
        echo "├── 192.168.1.10    🛡️  Файрволл (порты 80,443 закрыты)\n";
        echo "├── 192.168.1.45    🌐  Веб-сервер Apache 2.4.41 [УЯЗВИМ!]\n";
        echo "│   ├── Порт 80 ✅ HTTP (GET /admin = 200 OK)\n";
        echo "│   └── Порт 22 ✅ SSH (OpenSSH 7.9p1)\n";
        echo "├── 192.168.1.123   🗄️  MySQL 5.7.30 [СЛАБЫЙ ПАРОЛЬ]\n";
        echo "└── 192.168.1.200   💻  RDP CEO (заблокирован)\n\n";

        echo "[🎯 ЦЕЛЬ] Веб-сервер 192.168.1.45 — начни взлом админ-панели\n";
    }
    private function simulateHydra() {
        echo "[🔐 BRUTE-FORCE АТАКА НА АДМИН-ПАНЕЛЬ]\n";
        echo "[⚙️  Цель: http://192.168.1.45/admin/login.php]\n";
        $this->progressBar(100, 40, 'yellow');

        echo "\n[📊 СТАТИСТИКА АТАКИ:]\n";
        echo "• Попыток: 1,247 из 10,000\n";
        echo "• Скорость: 150 паролей/сек\n";
        echo "• Словарь: rockyou.txt (14M паролей)\n\n";

        echo "[✅ УСПЕХ] Логин: admin | Пароль: P@ssw0rd123\n";
        echo "[🔓 ДОСТУП] Админ-панель открыта! Логи сервера доступны\n\n";

        echo "[📋 В ЛОГАХ НАЙДЕНО:]\n";
        echo "• Подозрительный IP: 185.220.101.XX (Восточная Европа)\n";
        echo "• Фишинговый email: fake-hr@megacorp-security.com\n";
        echo "• SQL запрос с ошибкой в search.php\n\n";

        echo "[🎯 СЛЕДУЮЩАЯ ЦЕЛЬ] Проанализируй уязвимость в search.php\n";
    }

    private function simulateSqlmap() {
        echo "[💉 SQL-ИНЪЕКЦИЯ В БАЗУ ДАННЫХ]\n";
        echo "[🎯 Цель: http://192.168.1.45/search.php?query=1']\n";
        $this->progressBar(100, 45, 'red');

        echo "\n[📊 РЕЗУЛЬТАТЫ СКАНИРОВАНИЯ:]\n";
        echo "[✅ УЯЗВИМОСТЬ] SQL Injection (MySQL/PostgreSQL)\n";
        echo "[🔍 БАЗЫ ДАННЫХ] 3 базы найдено:\n";
        echo "  • corporate_db\n";
        echo "  • financial_data\n";
        echo "  • employee_records\n\n";

        echo "[📋 ИЗВЛЕЧЕННЫЕ ДАННЫЕ:]\n";
        echo "• Таблица 'users': 247 записей сотрудников\n";
        echo "• Таблица 'transactions': подозрительный перевод $5M\n";
        echo "• Получатель: Offshore_Account_XX (Кипр)\n\n";

        echo "[💰 ФИНАНСОВАЯ ИНФОРМАЦИЯ:]\n";
        echo "• Сумма: $5,000,000\n";
        echo "• Дата: 2025-12-07 23:45 UTC\n";
        echo "• IP отправителя: 185.220.101.XX\n";
        echo "• Примечание: 'Project Freedom'\n\n";

        echo "[🎯 СЛЕДУЮЩАЯ ЦЕЛЬ] Найди личную почту CEO для фишинга\n";
    }
    private function simulatePhish() {
        echo "[📧 СОЦИАЛЬНАЯ ИНЖЕНЕРИЯ — ФИШИНГ CEO]\n";
        echo "[👤 Цель: john.doe@megacorp.com (CEO)\n";
        $this->progressBar(100, 35, 'purple');

        echo "\n[📧 СОЗДАНЫ ФИШИНГОВЫЕ ШАБЛОНЫ:]\n";
        echo "1. 'Срочное обновление безопасности' (IT-отдел)\n";
        echo "2. 'Подтверждение финансовой операции' (Банк)\n";
        echo "3. 'Экстренное собрание правления' (Коллега)\n\n";

        echo "[🎯 ВЫБРАН ШАБЛОН #1] 'Срочное обновление безопасности'\n";
        echo "[📨 ОТПРАВЛЕНО] От: security@megacorp.com | Тема: Критическое обновление\n\n";

        echo "[⏱️ РЕАКЦИЯ ЦЕЛИ:]\n";
        echo "• Время отклика: 2 минуты 14 секунд\n";
        echo "• CEO кликнул по ссылке: fake-security-update.com\n";
        echo "• Введён код 2FA: 748392\n\n";

        echo "[🔓 ДОСТУП К ПОЧТЕ] john.doe@megacorp.com\n";
        echo "[📧 КЛЮЧЕВОЕ ПИСЬМО НАЙДЕНО:]\n";
        echo "От: anonymous@protonmail.com\n";
        echo "Тема: 'Ваша доля — 30% от $5M. Аэропорт через 2 часа'\n";
        echo "Содержание: 'Билет на имя John Doe, рейс в Кипр...'\n\n";

        echo "[🎯 СЛЕДУЮЩАЯ ЦЕЛЬ] Расшифруй ransomware на главном сервере\n";
    }

    private function simulateDecrypt() {
        echo "[🔓 РАСШИФРОВКА RANSOMWARE]\n";
        echo "[💻 Цель: Главный сервер MegaCorp (encrypted_data.enc)\n";
        $this->progressBar(100, 60, 'blue');

        echo "\n[🔍 АНАЛИЗ ВРЕДОНОСНОГО ПО:]\n";
        echo "• Алгоритм: AES-256-CBC\n";
        echo "• Ключ: 32 байта (SHA-256 от пароля CEO)\n";
        echo "• Файлы зашифровано: 1,247,892\n";
        echo "• Размер: 2.4 TB критических данных\n\n";

        echo "[🔑 ПОИСК КЛЮЧА ДЕШИФРОВКИ:]\n";
        echo "• Проверены личные файлы CEO... ✅\n";
        echo "• Найден пароль: 'MySecretKey2025!'\n";
        echo "• Генерация ключа SHA-256... ✅\n\n";

        echo "[🔓 ПРОЦЕСС ДЕШИФРОВКИ...]\n";
        echo "♦️ Пожалуйста, подождите...\n";
        sleep(3);
        echo "[✅ УСПЕШНО] Данные восстановлены!\n\n";
    }
    private function saveProgress() {
        file_put_contents($this->saveFile, json_encode($this->playerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function loadSave() {
        if (file_exists($this->saveFile)) {
            $data = json_decode(file_get_contents($this->saveFile), true);
            if ($data) {
                $this->playerData = array_merge($this->playerData, $data);
                $this->currentMission = $this->playerData['missions_completed'] + 1;
                if ($this->currentMission > 5) $this->currentMission = 5;
                echo "[💾 ПРОГРЕСС ЗАГРУЖЕН] Миссия ". $this->currentMission. "/5\n\n";
            }
        }
    }

    private function completeGame() {
        $totalTime = time() - $this->playerData['start_time'];
        $minutes = floor($totalTime / 60);
        $seconds = $totalTime % 60;

        echo "\n";
        echo "╔══════════════════════════════════════════════════════════════════════╗\n";
        echo "║                            МИССИЯ ВЫПОЛНЕНА!                         ║\n";
        echo "║                   🏆 КОМАНДА MEGA CORP СПАСЕНА 🏆                    ║\n";
        echo "╚══════════════════════════════════════════════════════════════════════╝\n\n";

        echo "[🎉 ПОЗДРАВЛЯЕМ! Ты раскрыл крупнейшую кибератаку 2025 года!]\n\n";

        echo "[📊 СТАТИСТИКА ПРОХОЖДЕНИЯ:]\n";
        echo "• Время: ". $minutes. "м ". $seconds. "с\n";
        echo "• Ранг: ". $this->playerData['rank']. "\n";
        echo "• Миссии: 5/5 завершено\n";
        echo "• Достижения: ". count($this->playerData['achievements']). "/5\n\n";

        echo "[🏅 КОНЕЧНАЯ КОНКОВКА:]\n";
        if ($totalTime < 600) {
            echo "🌟 ЛЕГЕНДАРНАЯ СКОРОСТЬ! Ты — лучший пентестер в мире!\n";
            echo "💼 Предложение: FBI Cyber Division\n";
        } elseif ($totalTime < 1200) {
            echo "⭐️ ОТЛИЧНАЯ РАБОТА! Корпорация MegaCorp предлагает постоянную работу\n";
            echo "💰 Бонус: $250,000 за спасение компании\n";
        } else {
            echo "✅ ХОРОШАЯ РАБОТА! Атакующие арестованы, данные восстановлены\n";
            echo "📜 Сертификат: Certified Ethical Hacker (CEH)\n";
        }

        echo "\n[📜 КРЕДИТЫ:]\n";
        echo "• Разработчик: web-pentest\n";
        echo "• GitHub: github.com/web-pentest/CodeStalker\n";
        echo "• Discord: discord.gg/hack___________the___________box\n\n";

        echo "[🎮 ИГРА ЗАВЕРШЕНА! Перезапусти для новой игры или внеси вклад в проект]\n\n";

        $this->saveProgress();
        readline("Нажми Enter для выхода...");
        exit(0);
    }
    private function showHelp() {
        echo "\n[📖 СПРАВКА ПО CODESTALKER v1.1]\n";
        echo "══════════════════════════════════════════════════════════════════════\n\n";

        echo "[🎯 ОСНОВНЫЕ КОМАНДЫ ПО МИССИЯМ:]\n";
        foreach ($this->missions as $num => $mission) {
            $status = ($num <= $this->playerData['missions_completed'])? "✅": "🔒";
            echo "  $status Миссия $num: ". $mission['command']. " — ". $mission['name']. "\n";
        }
        echo "\n[📊 ИНФОРМАЦИОННЫЕ КОМАНДЫ:]\n";
        echo "  • help     — показать эту справку\n";
        echo "  • whoami   — информация о профиле\n";
        echo "  • status   — текущий статус миссии\n";
        echo "  • about    — информация об авторе\n";
        echo "  • clear    — очистить экран\n";
        echo "  • exit     — выход из игры\n\n";

        echo "[💡 СОВЕТЫ:]\n";
        echo "• Выполняй миссии по порядку (1→2→3→4→5)\n";
        echo "• Каждая миссия даёт прогресс и достижения\n";
        echo "• Следи за подсказками в выводе команд\n";
        echo "• Время прохождения влияет на концовку!\n\n";

        if ($this->playerData['missions_completed'] < 5) {
            $nextMission = $this->missions[$this->currentMission];
            echo "[🎯 ТЕКУЩАЯ ЗАДАЧА] Используй: ". $nextMission['command']. "\n\n";
        }
    }
    private function showPlayerInfo() {
        echo "\n[👤 ПРОФИЛЬ ИГРОКА: ". $this->playerData['name']. "]\n";
        echo "══════════════════════════════════════════════════════════════════════\n";
        echo "[🎖️  РАНГ] ". $this->playerData['rank']. "\n";
        echo "[📈 ПРОГРЕСС] ". $this->playerData['progress']. "%\n";
        echo "[🎯 МИССИИ] ". $this->playerData['missions_completed']. "/5\n";
        echo "[⏱️  ВРЕМЯ] ". gmdate('H:i:s', time() - $this->playerData['start_time']). "\n";

        echo "\n[🏆 ДОСТИЖЕНИЯ (". count($this->playerData['achievements']). "/5):]\n";
        foreach ($this->playerData['achievements'] as $ach) {
            echo "  • $ach\n";
        }

        if (empty($this->playerData['achievements'])) {
            echo "  • Пока нет достижений. Начни с 'nmap'!\n";
        }
        echo "\n";
    }

    private function showStatus() {
        echo "\n[📊 ТЕКУЩИЙ СТАТУС]\n";
        echo "══════════════════════════════════════════════════════════════════════\n";
        echo "[🎯 Миссия] ". $this->currentMission. "/5: ". $this->missions[$this->currentMission]['name']. "\n";
        echo "[📋 Команда] ". $this->missions[$this->currentMission]['command']. "\n";
        echo "[📈 Прогресс] ". $this->playerData['progress']. "%\n";
        echo "[🎖️  Ранг] ". $this->playerData['rank']. "\n";
        echo "\n[💡 Что делать:] Введи '". $this->missions[$this->currentMission]['command']. "'\n\n";
    }
    private function showAbout() {
        echo "\n[👨‍💻 ОБ АВТОРЕ]\n";
        echo "══════════════════════════════════════════════════════════════════════\n";
        echo "[🧑 Имя] web-pentest\n";
        echo "[💼 Специализация] Web Penetration Testing | PHP Developer\n";
        echo "[🐧 Платформы] Linux Enthusiast | CLI Tools Creator\n";
        echo "[🌐 GitHub] github.com/web-pentest/CodeStalker\n";
        echo "[💬 Discord] discord.gg/hack___________the___________box\n";
        echo "\n[📚 ПРОЕКТ CodeStalker:]\n";
        echo "• Версия: 1.1 (Полная)\n";
        echo "• Язык: PHP 7.1+\n";
        echo "• Лицензия: MIT\n";
        echo "• Цель: Обучение кибербезопасности через игру\n\n";
        echo "[⭐️ ПОДДЕРЖИ ПРОЕКТ:]\n";
        echo "• Поставь звезду на GitHub ⭐️\n";
        echo "• Поделись с друзьями\n";
        echo "• Внеси вклад в разработку!\n\n";
    }
}

// Закрытие класса

// Запуск игры
if (php_sapi_name() == 'cli') {
    echo "CodeStalker v1.1 - Кибер-расследование\n";
    echo "=======================================\n\n";

    $game = new CodeStalker();
    $game->run();
} else {
    die("Запускай только в терминале! php codestalker_fixed.php\n");
}?>
