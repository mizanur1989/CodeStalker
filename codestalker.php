<?php
// CodeStalker v1.0 - CLI Hacking Game
// Создатель: web-pentest
// GitHub: https://github.com/web-pentest

class CodeStalker {
    private $currentMission = 1;
    private $playerData = [
        'name' => 'GhostHacker',
        'rank' => 'Новичок',
        'progress' => 0,
        'achievements' => []
    ];

    public function run() {
        $this->showIntro();
        while (true) {
            $command = readline('$ ');
            $this->processCommand($command);
        }
    }
    private function showIntro() {
        echo "\n";
        echo "╔══════════════════════════════════════════════════════════════╗\n";
        echo "║           [CODESTALKER v1.0]  ████  MEGA CORP BREACH ████    ║\n";
        echo "╚══════════════════════════════════════════════════════════════╝\n";
        echo "\n[Брифинг миссии]\n";
        echo "Системы MegaCorp взломаны. Переведено \$5M на офшоры.\n";
        echo "Генеральный директор пропал, активировано ransomware.\n";
        echo "Найди источник атаки и останови её!\n\n";
        echo "[Статус] Ты: ". $this->playerData['name']. " | Ранг: ". $this->playerData['rank']. "\n";
        echo "[Цель] Сетевая инфраструктура: 192.168.1.0/24\n\n";
        echo "[Подсказка] Введи 'help' для справки.\n\n";
    }
    private function processCommand($command) {
        $cmd = strtolower(trim($command));

        switch (true) {
            case strpos($cmd, 'nmap')!== false:
                $this->simulateNmap();
                break;
            case $cmd == 'whoami':
                $this->showPlayerInfo();
                break;
            case $cmd == 'help':
                $this->showHelp();
                break;
            case $cmd == 'about':
            case $cmd == 'profile':
                $this->showAbout();
                break;
            case $cmd == 'exit':
                echo "[Сеанс завершен]\n";
                exit(0);
            default:
                echo "[Ошибка] Команда не распознана. Введите 'help' для списка команд.\n";
        }
    }

    private function showPlayerInfo() {
        echo "\n[Система] Ты: ". $this->playerData['name']. "\n";
        echo "[Ранг] ". $this->playerData['rank']. "\n";
        echo "[Прогресс] ". $this->playerData['progress']. "%\n";
        echo "[Достижения] ". count($this->playerData['achievements']). " разблокировано\n";
    }
    private function showHelp() {
        echo "\n[Справка по игре]\n";
        echo "Ты — этический хакер, расследующий кибератаку на MegaCorp.\n";
        echo "Твоя цель — найти уязвимости, взломать серверы, остановить атаку.\n\n";

        echo "Доступные команды:\n";
        echo "  nmap [опции]  - сканирование сети\n";
        echo "  whoami       - информация о игроке\n";
        echo "  about, profile - информация об авторе\n";
        echo "  help         - показать эту справку\n";
        echo "  exit         - выйти из игры\n\n";

        echo "Советы:\n";
        echo "• Начни с 'nmap -sV 192.168.1.0/24' для сканирования сети\n";
        echo "• Ищи серверы с пометкой [Уязвим]\n";
        echo "• Каждая миссия имеет таймер — действуй быстро!\n\n";
    }

    private function showAbout() {
        echo "\n[Об авторе]\n";
        echo "Создатель: web-pentest\n";
        echo "GitHub: https://github.com/web-pentest\n";
        echo "Проект: CLI игра 'CodeStalker'\n";
        echo "Спасибо за интерес! Ставьте звезды ⭐️ и делитесь!\n\n";
    }
    private function simulateNmap() {
        echo "\n[Запуск сканирования сети на 256 узлов...]\n";
        $this->progressBar(100, 30);

        echo "\nОбнаружены узлы:\n";
        echo "├── 192.168.1.10    - Межсетевой экран (порты 80,443 закрыты)\n";
        echo "├── 192.168.1.45    - Веб-сервер (Apache 2.4.41) [Уязвим]\n";
        echo "│   ├── Порт 80/открыт - HTTP (GET /admin возвращает 200)\n";
        echo "│   └── Порт 22/открыт - SSH (OpenSSH 7.9)\n";
        echo "├── 192.168.1.123   - База данных (MySQL 5.7.30) [Слабый пароль]\n";
        echo "└── 192.168.1.200   - Терминал CEO (Windows RDP) [Заблокирован]\n\n";

        echo "[Информация] Найден целевой сервер: 192.168.1.45 (Веб-сервер)\n";
        echo "[Задача] Следующий шаг: взломай панель администратора или продолжай сканирование\n";

        $this->playerData['progress'] += 15;
        if ($this->playerData['progress'] >= 100) {
            $this->completeMission();
        }
    }

    private function progressBar($total, $width = 50) {
        for ($i = 0; $i <= $total; $i += 5) {
            $percent = $i;
            $filled = intval($width * $percent / 100);
            $bar = str_repeat('█', $filled). str_repeat('░', $width - $filled);
            echo "\r[Сканер] [$bar] $percent%";
            usleep(50000);
        }
        echo "\n";
    }
    private function completeMission() {
        echo "\n[МИССИЯ ВЫПОЛНЕНА!]\n";
        echo "Сканирование сети прошло успешно.\n";
        echo "Разблокировано достижение: 'Разведчик сети'\n";
        $this->playerData['achievements'][] = 'Разведчик сети';
        $this->playerData['rank'] = 'Хактивист';
        echo "[Новый ранг] Хактивист — открыт доступ к продвинутым инструментам\n\n";
    }
} // ← Закрываем класс!

// Запуск игры (вне класса!)
echo "CodeStalker - Кибер-расследование\n";
echo "=====================================\n\n";

$game = new CodeStalker();
$game->run();?>
