<?php

class StatusRepository extends DbRepository
{
    //ユーザIDと投稿内容を引数にとりインサートするメソッド
    public function insert($user_id, $title, $body)
    {
        $now = new DateTime();

        $sql = "
            INSERT INTO status(user_id, title, body, created_at)
                VALUES(:user_id, :title, :body, :created_at)
        ";

        $stmt = $this->execute($sql, array(
            ':user_id'    => $user_id,
            ':title'      => $title,
            ':body'       => $body,
            ':created_at' => $now->format('Y-m-d H:i:s'),
        ));
    }

    //全ての投稿を取得する*WHERE
    public function fetchAllPersonalArchivesByUserId($user_id)
    {
        $sql = "
            SELECT a.*, u.user_name
            FROM status a
                LEFT JOIN user u ON a.user_id = u.id
                ORDER BY a.created_at DESC
        ";

        return $this->fetchAll($sql, array(':user_id' => $user_id));
    }

    //ユーザのIDに一致する投稿を、投稿日の降順に全件取得
    public function fetchAllByUserId($user_id)
    {
        $sql = "
            SELECT a.*, u.user_name
                FROM status a
                    LEFT JOIN user u ON a.user_id = u.id
                WHERE u.id = :user_id
                ORDER BY a.created_at DESC
        ";

        //var_dump($this->fetchAll($sql, array(':user_id' => $user_id)));
        return $this->fetchAll($sql, array(':user_id' => $user_id));
    }

    //投稿IDとユーザのIDに一致するレコードを１件取得
    public function fetchByIdAndUserName($id, $user_name)
    {
        $sql = "
            SELECT a.* , u.user_name
                FROM status a
                    LEFT JOIN user u ON u.id = a.user_id
                WHERE a.id = :id
                    AND u.user_name = :user_name
        ";

        return $this->fetch($sql, array(
            ':id'        => $id,
            ':user_name' => $user_name,
        ));
    }

    //ユーザIDと投稿内容を引数にとりインサートするメソッド
    public function update($id, $title, $body)
    {
        $now = new DateTime();

        $sql = "
            UPDATE status
                SET title = :title, body = :body, created_at = :created_at
                WHERE id = :id
        ";

        $stmt = $this->execute($sql, array(
            ':id'    => $id,
            ':title' => $title,
            ':body'       => $body,
            ':created_at' => $now->format('Y-m-d H:i:s'),
        ));
    }

    //投稿を削除
    public function delete($id)
    {
        $sql = "
            DELETE FROM status
                WHERE id = :id 
        ";

        $stmt = $this->execute($sql, array(
            ':id'    => $id,
        ));
    }
}
