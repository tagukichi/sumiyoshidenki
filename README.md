# 住吉電機株式会社 サイトリニューアル

[現行サイト（WordPress + Elementor）](https://www.sumiyoshidennki.com/) のデザイン全面リニューアル用リポジトリです。
コンテンツはWordPressエクスポートXML（2026-06-10）から移植済み。

- **静的サイト版**（リポジトリ直下）… デザイン・機能の確認用。GitHub Pages で公開中
- **WordPressテーマ**（`wp-theme/sumiyoshi-denki/`）… 本番用。静的版と同じデザイン・同じURL構成

```
/                       静的サイト（index.html ほか）
assets/                 CSS・JS（静的サイトとテーマで共通）
wp-theme/sumiyoshi-denki/  WordPressテーマ本体
tools/build.py          静的サイトの全HTMLを生成
tools/build_theme.py    テーマへ assets を同期・エディタ用CSS生成・zip作成
```

---

## WordPressテーマ

### 導入手順

作業前に、必ずサーバーのバックアップ（データベース＋ファイル）を取ってください。

1. `python3 tools/build_theme.py` で `dist/sumiyoshi-denki.zip` を作成
2. 管理画面 **外観 > テーマ > 新規追加 > テーマのアップロード** で zip をアップロード
3. すぐに有効化せず、**「ライブプレビュー」で表示を確認**してから有効化
4. **固定ページ「お問い合わせ」を「公開」に変更**（現行サイトでは非公開のため、訪問者には表示されません。ヘッダー・フッターからリンクしています）
5. 表示が崩れる・404 になる場合は **設定 > パーマリンク** を開いて「変更を保存」を1回押す

### プラグインとの関係

| プラグイン | 扱い |
|---|---|
| ACF | **有効のままにしてください。** News・施工実績の投稿タイプ、求人ページの入社日（勤続年数）を管理しています。ACF を停止した場合はテーマが同じ投稿タイプを登録するので、URL は変わりません |
| Contact Form 7 | お問い合わせ（ID 362）・求人応募（ID 394）フォームをテーマのデザインで表示します。フォームを作り直した場合は `wp-config.php` に `define('SUMIYOSHI_CF7_CONTACT', 新ID);` / `define('SUMIYOSHI_CF7_RECRUIT', 新ID);` を追加 |
| Elementor / Elementor Pro | テーマが担当するページ（トップ・会社案内系・求人・お問い合わせ・News・施工実績・ブログ）は、Elementor の設定が残っていてもテーマのデザインで表示されます。確認後は **停止して問題ありません** |
| SiteOrigin Page Builder | 旧施工実績の写真（panels_data）は、プラグインが無くてもテーマが写真ギャラリーとして表示します |
| Yoast SEO など | SEO プラグインがある場合、テーマは meta description を出力しません（重複防止） |
| WPCode | 求人ページの `[tenure]` ショートコードが WPCode 側で登録済みならそちらを優先します |

### テンプレート一覧

| URL | テンプレート | 内容の編集方法 |
|---|---|---|
| `/` | `front-page.php` | お知らせ6件・施工実績4件は投稿から自動表示。スライダー画像は同ファイル先頭の配列 |
| `/news/`・`/news/記事/` | `archive.php`・`single.php` | 管理画面「News & Topics」で投稿 |
| `/jisseki/`・`/jisseki/記事/` | `archive-jisseki.php`・`single-jisseki.php` | 管理画面「施工実績」で投稿。タイトルは `工事名<br>令和◯年◯月` の形式（一覧で工事名と時期が分かれて表示） |
| `/information/` | `page-information.php` | — |
| `/information/greeting/` | `page-greeting.php` | テンプレート内の本文を編集 |
| `/information/company/` | `page-company.php` | ファイル先頭の `$sumiyoshi_rows`（表の項目）を編集 |
| `/information/history/` | `page-history.php` | ファイル先頭の `$sumiyoshi_history` に1行追加 |
| `/information/access/` | `page-access.php` | — |
| `/recruit/` | `page-recruit.php` | 募集要項・社員インタビューはテンプレート内。勤続年数は ACF の入社日から自動計算 |
| `/contact/` | `page-contact.php` | フォーム項目は Contact Form 7 側で編集 |
| その他の固定ページ | `page.php` | エディタで書いた内容をそのまま表示 |
| ブログ（通常の投稿） | `archive.php`・`single.php` | 「投稿」で書く場合、一覧ページは **設定 > 表示設定 > 投稿ページ** で固定ページを指定 |

- メニューは **外観 > メニュー** で「グローバルナビ」「フッターナビ」を設定できます。未設定の場合は現行サイトと同じ構成のメニューが自動で表示されます
- ロゴは **外観 > カスタマイズ > サイト基本情報 > ロゴ** で変更可能（未設定時は現行のロゴを使用）
- フッターの「プライバシーポリシー」は **設定 > プライバシー** でページを指定すると表示されます

### ブログ（記事）の書き方

記事本文は `.article__body.entry-content` で囲んでいるため、**ブロックエディタで普通に書くだけで**サイトのデザインになります。
エディタの画面にも同じ見た目（`assets/css/editor-style.css`）が反映されるので、公開後の見た目を確認しながら書けます。

- 見出し2（アンバーの縦バー＋グレー帯）/ 見出し3（下線＋アンバーのアクセント）/ 見出し4（角マーク）
- 太字（マーカー風）・リンク・箇条書き・番号リスト・引用・表・画像（キャプション付き）・区切り線・ボタン
- 文字色・背景色のパレットはサイトの配色（ネイビー・ブルー・アンバーなど）に揃えてあります
- 記事ページの下に「前の記事／次の記事」を自動表示

### CSS・JS を修正したとき

CSS・JS は静的サイトと共通です。`assets/` を編集したら次の2つを実行してください。

```bash
python3 tools/build.py        # 静的サイトの再生成
python3 tools/build_theme.py  # テーマへ同期・エディタ用CSS再生成・zip作成
```

`wp-theme/sumiyoshi-denki/assets/` と `editor-style.css` はこのスクリプトが上書きするので、直接編集しないでください。

### 動作確認した環境

WordPress 6.5 + PHP 8.4 のローカル環境に、エクスポートXMLから抽出した内容（固定ページ・News 14件・施工実績13件）を投入して、
全ページの表示（PC・スマホ）、ページ送り、管理画面メニューでの表示、PHP エラーが出ないことを確認しています。
本番のプラグイン（ACF・Contact Form 7・Elementor・Yoast）そのものは検証環境に入れられなかったため、
Contact Form 7 は出力HTMLを再現したもので見た目を確認しています。**本番ではライブプレビューで確認してから有効化してください。**

---

## 静的サイト版

全ページ相対パスのため、GitHub Pages（サブディレクトリ公開）、ローカルサーバー、
`index.html` をブラウザで直接開く（file://）のいずれでも閲覧できます。

```bash
python3 -m http.server 8000   # → http://localhost:8000
```

- 画像は現サイト（wp-content/uploads）のURLを直接参照しています。現サイトのサーバーは国外IPを遮断しているため、**日本国内からの閲覧でのみ画像が表示されます**
- 全HTMLは `tools/build.py` から生成しています。文言修正は build.py を編集して再生成してください
- ブログの装飾サンプルは `/styleguide.html`（テーマには含まれません）

## デザインの方向性

- カラー: 濃紺（信頼感）× アンバー（ロゴの稲妻イエロー）。告知バーは現サイトのオリーブを継承
- フォント: Noto Sans JP
- トップ: 縦書きキャッチコピー + フェードスライダー（現サイトのコピーをそのまま使用）
- レスポンシブ対応（1040px以下でハンバーガーメニュー）、スクロールフェードイン、固定ヘッダー
