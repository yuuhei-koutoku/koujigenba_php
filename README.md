# 工事現場情報サイト（生 PHP 版）

## 1.概要

工事現場で役立つスキルやテクニックなどの知識・情報を互いに共有するサイトです。このアプリケーションは Laravel などを用いて作成した工事現場情報サイトをフレームワークなしでリメイクしたものです。

↓ フレームワークありで作成した工事現場情報サイトはこちら<br>
GitHub：https://github.com/yuuhei-koutoku/cts-remake<br>
紹介記事：[Ruby・Rails で作ったポートフォリオを PHP・Laravel でリメイクしてみた](https://qiita.com/Yuhei_K/items/06805f3ac8607f77952f)

## 2. 要件、設計

### 前提

建設現場で働いている方々にとって、現場で活かせるスキルやテクニックは非常に有益な情報だが、ほとんど口頭で伝えられている。

### 課題

インターネット上で情報共有が行われれば、互いに新たな知見や視点を得られる可能性が高まるが、現状はクローズドなコミュニティの中でしか共有されていないため、機会損失となっている。

### 要件

工事現場で活かせるスキルやテクニックなどの情報を互いに共有できること。

### 設計

ユーザーがアカウントを作成できること。<br>
ユーザーが記事を新規投稿・編集・削除できること。<br>
管理者がユーザーや記事の情報を管理できること。

## 3.　使用技術、開発環境

- フロントエンド
  - HTML
  - CSS
  - Javascript
  - Twig 2.0
- バックエンド
  - PHP 8.2.0
- その他
  - MAMP
    - MySQL 5.7.39
    - Apache
  - Visual Studio Code
  - Git / GitHub / SourceTree
  - Bootstrap 5.0.2
  - Font Awesome 6.4.0

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
  - ユーザー登録
  - ログイン
  - マイページ
    - メールアドレス変更
    - パスワード変更
    - 退会
  - ログアウト
- 掲示板関連
  - 記事一覧
  - 新規投稿
  - 編集
  - 削除
  - 画像投稿
- 管理者関連
  - ユーザー一覧
    - ユーザー種別更新
    - ステータス更新
  - 全ての記事の削除権限

## 4. 工夫した点

- 同じようなコードを書く機会が多かったので、リファクタリングを積極的に行いました。できるだけ共通化を行うことで、修正や追加を正確且つスピーディーに行えるようなコードになるよう心がけました。
- 絶対にバグが発生しないよう、逐次挙動確認を行なったり、システムテストを実施したりしました。また、細かくバリデーションを実装することで、アプリケーションとデータベースの整合性を保つよう配慮しました。
  ![system_test](/backend//images/readme/system_test.png)

## 5. 苦労した点

- フレームワークを用いなかったため、自身が書くコード量が多くて大変でした。ほぼ全てのコードをゼロイチで書いたので、単純にコードの記述量が多かったです。

## 6. 反省点

- 実装した機能が少ないです。1 つ 1 つの機能をこだわって実装した結果、1 つの機能を実装するのに 1 週間以上時間を費やしてしまうこともあり、あまり多くの機能を実装することができませんでした。改めて、フレームワークの便利さを感じました。
- コーディングルールを設けていない且つフレームワークを用いていないため、スパゲッティコード化してしまいました。このアプリケーションでは、自分以外の人が開発することを想定していないです。極力わかりやすいディレクトリ構造やコードになるよう配慮しましたが、それでも他の人が見たらわかりにくいと思います。
- UI が雑です。余計な空白が多いですし、色のバランスも悪いです。Bootstrap のコードを多く用いているため、デザイン面でオリジナリティに欠けます。レスポンシブは未対応であるため、スタイル崩れも発生します。

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
