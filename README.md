# 住吉電機株式会社 サイトリニューアル（静的サイト版）

[現行サイト（WordPress）](https://www.sumiyoshidennki.com/) のデザイン全面リニューアル用の静的サイトです。
デザイン・機能の確認が済んだら、WordPressテーマへ変換する前提で構成しています。

## ローカルでの確認方法

ルート相対パス（`/assets/...`）を使用しているため、ローカルサーバーを立てて確認してください。

```bash
cd sumiyoshidenki
python3 -m http.server 8000
# → http://localhost:8000 を開く
```

## ディレクトリ構成（WordPressテーマ化を想定）

```
/
├── index.html                      # トップページ → front-page.php
├── business/index.html             # 事業内容 → page-business.php
├── works/index.html                # 施工実績 → page-works.php
├── news/index.html                 # お知らせ一覧 → home.php / archive.php
├── news/kawasaki-city-hall-solar.html  # 記事詳細 → single.php
├── information/company/index.html # 会社概要 → page-company.php
├── information/history/index.html # 沿革 → page-history.php
├── contact/index.html              # お問い合わせ → page-contact.php (CF7等)
└── assets/
    ├── css/style.css               # → テーマの style.css
    ├── js/main.js
    └── img/                        # 画像（現サイトから移植予定）
```

- 各HTMLの `<!-- ▼ header -->`〜`<!-- ▲ header -->` と `<!-- ▼ footer -->`〜 は、
  WP化時にそのまま `header.php` / `footer.php` へ切り出せるよう全ページ共通にしています。
- URL構造は現行サイトに確認できた範囲で合わせています（`/information/company/`、`/information/history/`）。

## デザインの方向性

- カラー: 濃紺（信頼感）× アンバー（電気・エネルギー）
- フォント: Noto Sans JP
- レスポンシブ対応（860px以下でハンバーガーメニュー）
- スクロール連動のフェードイン、固定ヘッダー

## ⚠️ 要確認事項（現行サイトの内容確認待ち）

開発環境から現行サイトへアクセスできず（海外IPからのアクセスが403でブロックされている模様。
レンタルサーバーの「国外IPアクセス制限」機能の可能性が高い）、検索エンジンに残っている情報のみで構築しています。

### 確認済みの情報（検索結果ベース）
- 会社名: 住吉電機株式会社（SUMIYOSHI ELECTRIC CO.,LTD）
- 所在地: 〒213-0024 神奈川県川崎市高津区明津14番地1
- TEL: 044-755-6161
- 代表者: 代表取締役 高橋賢次
- 創立: 昭和28年11月1日 ／ 会社設立: 昭和38年
- 資本金: 5,150万円 ／ 従業員数: 10名（2025年3月現在）
- 事業内容: 電気配線工事・計装工事・消防施設工事・電気通信工事／貸ビル業
- 既存ページ: トップ / 会社概要(/information/company/) / 沿革(/information/history/)
- 既存記事: 「川崎市新本庁舎太陽光発電設備設置工事が完成しました」(?p=218)

### 未確認・要提供（HTML内に `TODO` / `要確認` コメントで明示）
- [ ] トップページの実際の構成・キャッチコピー・掲載文
- [ ] グローバルナビの正確なメニュー構成（現状は推定）
- [ ] 沿革ページの年表全文
- [ ] 会社概要の全項目（建設業許可番号・取引銀行など）
- [ ] お知らせの全記事（日付・本文・写真）
- [ ] 施工実績ページの有無と内容
- [ ] お問い合わせ手段（フォームの有無・項目）
- [ ] 写真・ロゴ等の画像素材
- [ ] 営業時間・FAX番号

**確認方法の候補:**
1. WordPress管理画面から「ツール → エクスポート」でXMLを書き出して共有
2. 各ページのスクリーンショットまたはテキストの共有
3. サーバーの「国外IPアクセス制限」を一時的に解除

## WordPressテーマ化の手順（予定）

1. `style.css` にテーマヘッダーを追加、`functions.php` 作成（メニュー・アイキャッチ・CSS/JS読込）
2. header / footer を `header.php` / `footer.php` へ分離
3. 固定ページテンプレート（会社概要・沿革・事業内容ほか）作成
4. お知らせを `home.php` / `single.php` 化（一覧・詳細をループ出力）
5. お問い合わせフォームをプラグイン（Contact Form 7 等）へ接続
6. メニューを `wp_nav_menu()` 化、パーマリンク設定を現行URLに合わせる
