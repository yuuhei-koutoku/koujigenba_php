# 工事現場情報サイト（生 PHP 版）

## 1.概要

工事現場の役立つスキルやテクニックなどの知識・情報を互いに共有するサイトです。このアプリケーションは Laravel などを用いて作成した工事現場情報サイトをフレームワークなしでリメイクしたものです。

↓ フレームワークありで作成した工事現場情報サイトはこちら<br>
GitHub：https://github.com/yuuhei-koutoku/cts-remake<br>
紹介記事：[Ruby・Rails で作ったポートフォリオを PHP・Laravel でリメイクしてみた](https://qiita.com/Yuhei_K/items/06805f3ac8607f77952f)

## 2. 上流工程

### 前提

建設現場で働いている方々にとって、現場で活かせるスキルやテクニックは非常に有益な情報だが、ほとんど口頭で伝えられている。

### 課題

インターネット上で情報共有が行われれば、互いに新たな知見や視点を得られる可能性が高まるが、現状はクローズドなコミュニティの中でしか共有されていないため、機会損失となっている。

### 要件

工事現場で活かせるスキルやテクニックなどの情報を互いに共有できること。

### 設計

ユーザーがアカウントを作成できること。<br>
ユーザーが記事を新規投稿・編集・削除できること。

## 3.　使用技術

- フロントエンド
  - HTML
  - Twig 2.0
  - Bootstrap 5.0.2
- バックエンド
  - PHP 8.2.0
- その他

  - Git / GitHub / SourceTree
  - MySQL 5.7.39
  - Apache

## 4. 環境構築手順

### 1. GitHub よりダウンロード

```
git clone https://github.com/yuuhei-koutoku/koujigenba_php.git
```

### 2. Twig をインストール

```
composer install
```

### 3. データベースを構築

詳細は「7. データベース設定」をご確認ください。

## 5. 機能一覧

- ユーザー関連
  - ユーザー登録機能
  - ログイン機能
  - ログアウト機能
  - マイページ（未実装）
    - メールアドレス変更（未実装）
    - 退会（未実装）
- 記事投稿関連
  - 記事一覧
  - 新規投稿
  - 編集
  - 削除
  - 画像投稿
- 管理者機能（未実装）
- ユーザー一覧（未実装）
  - 強制的に退会（未実装）
- 記事一覧（実装済）
  - 記事削除（未実装）

## 4. 工夫した点

- 同じようなコードを書く機会が多かったので、リファクタリングを積極的に行いました。できるだけ共通化を行うことで、修正や追加を正確且つスピーディーに行えるようなコードになるよう心がけました。
- 絶対にバグが発生しないよう、逐次挙動確認を行なったり、システムテストを実施したりしました。また、細かくバリデーションを行うことで、アプリケーションとデータベースの整合性を保つよう配慮しました。

## 5. 苦労した点

- フレームワークを用いなかったため、自身が書くコード量が多くて大変でした。ほぼ全てのコードをゼロイチで書いたので、単純にコードの記述量が多かったです。

## 6. 反省点（3 月 27 日時点）

- コードの記述量が多かったため、開発スピードが遅かったです。1 つの機能を実装するのに 1 週間以上時間を費やしてしまうこともありました。改めて、フレームワークの便利さを感じました。
- 実装した機能が少ないです。1 つ 1 つの機能をこだわって実装したため、あまり多くの機能を実装することができませんでした。
- プリペアードステートメントを適切に使用することができませんでした。PDO を用いて DB へクエリを発行する際に、query を用いる場合と、prepare・execute を用いる場合があります。それぞれどのようなケースでどちらのメソッドを使用すれば良いかよくわかりませんでした。
- UI が雑です。余計な空白が多いですし、色のバランスも悪いです。Bootstrap のコードを多く用いているため、デザイン面でオリジナリティに欠けます。
- Git の運用も雑でした。大量のコードを 1 つのコミットでプッシュしたことがありました。よりコミットを細かく分けてプッシュするべきでした。また、序盤の開発はブランチの運用が雑でした。main ブランチをベースに開発していた点が良くなかったです。そのため、途中から develop ブランチをベースに機能開発を実施しました。

## 7. データベース設定

```sql
-- データベースを作成する
CREATE DATABASE koujigenba_db DEFAULT CHARACTER SET utf8;

-- 作成したデータベースのみ使用できるユーザーを作成し、パスワードも設定する
GRANT ALL PRIVILEGES ON koujigenba_db.* TO koujigenba_user@'localhost' IDENTIFIED BY 'koujigenba_pass' WITH GRANT OPTION;

-- ユーザーテーブル
CREATE TABLE users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  last_name VARCHAR(30) NOT NULL,
  first_name VARCHAR(30) NOT NULL,
  email VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  admin TINYINT(1) UNSIGNED NOT NULL,
  delete_flg TINYINT(1) UNSIGNED NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 記事テーブル
CREATE TABLE articles (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  image VARCHAR(50) NOT NULL,
  title VARCHAR(100) NOT NULL,
  content TEXT NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- セッションテーブル
CREATE TABLE sessions (
  user_id int unsigned not null,
  session_key varchar(32)
);
```
