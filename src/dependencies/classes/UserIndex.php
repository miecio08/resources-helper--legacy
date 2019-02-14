<?php

class UserIndex {

    /** @var PDO $pdo */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function updateLastSeenTimestampByPlayerID(int $uid, int $timestamp): bool {
        $stmt = $this->pdo->prepare('UPDATE `userIndex` SET `lastSeen` = :lastSeen WHERE `uid` = :uid');

        return $stmt->execute(['lastSeen' => $timestamp, 'uid' => $uid]);
    }

    public function getPlayerIDByName(string $userName): int {
        $stmt = $this->pdo->prepare('SELECT `uid` FROM `userIndex`  WHERE `userName` = :userName');
        $stmt->execute(['userName' => $userName]);

        if($stmt->rowCount() > 0) {
            return $stmt->fetch()['uid'];
        }

        return 0;
    }

    /**
     * Removes server-side unsupported unicode characters from names
     *
     * @param string $userName
     *
     * @return string
     */
    public function escapeUserName(string $userName): string {
        $escapedUserName = preg_replace('%(?:\xF0[\x90-\xBF][\x80-\xBF]{2} | [\xF1-\xF3][\x80-\xBF]{3} | \xF4[\x80-\x8F][\x80-\xBF]{2})%xs', '', $userName);

        if(is_string($escapedUserName)) {
            return $escapedUserName;
        }

        return '';
    }

    public function addPlayer(string $userName, $lastSeen = 0): int {
        $stmt = $this->pdo->prepare('INSERT INTO `userIndex` (`userName`, `lastSeen`) VALUES(:userName, :lastSeen)');
        $stmt->execute([
            'userName' => $userName,
            'lastSeen' => $lastSeen > 0 ? $lastSeen : time(),
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
