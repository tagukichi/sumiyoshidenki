# 住吉電機株式会社 サイトリニューアル（静的サイト版）

[現行サイト（WordPress + Elementor）](https://www.sumiyoshidennki.com/) のデザイン全面リニューアル用の静的サイトです。
コンテンツはWordPressエクスポートXML（2026-06-10）から移植済み。
デザイン・機能の確認が済んだら、WordPressテーマへ変換する前提で構成しています。

## 確認方法

全ページ相対パスのため、GitHub Pages（サブディレクトリ公開）、ローカルサーバー、
`index.html` をブラウザで直接開く（file://）のいずれでも閲覧できます。

```bash
cd sumiyoshidenki
python3 -m http.server 8000
# → http://localhost:8000 を開く
```

※画像は現サイト（wp-content/uploads）のURLを直接参照しています。
現サイトのサーバーは国外IPを遮断しているため、**日本国内からの閲覧でのみ画像が表示されます**。
WPテーマ化時は同じメディアライブラリを使うため、そのまま動作します。

## ページの再生成

全HTMLは `tools/build.py` から生成しています。文言修正などは build.py を編集して再生成してください。

```bash
python3 tools/build.py
```

## サイト構成（現行サイトのURL構造に準拠）

| パス | ページ | WP化時のテンプレート |
|---|---|---|
| `/` | トップページ | `front-page.php` |
| `/news/` + 記事14件 | News & Topics | `archive-news.php` / `single-news.php` |
| `/jisseki/` + 詳細13件 | 施工実績 | `archive-jisseki.php` / `single-jisseki.php` |
| `/information/` | 会社案内 | `page-information.php` |
| `/information/greeting/` | 代表挨拶 | 固定ページ |
| `/information/company/` | 会社概要 | 固定ページ |
| `/information/history/` | 沿革 | 固定ページ |
| `/information/access/` | アクセス | 固定ページ |
| `/recruit/` | 求人情報 | 固定ページ + CF7「求人用フォーム」 |
| `/contact/` | お問い合わせ | 固定ページ + CF7「お問い合わせフォーム」 |

- 各HTMLの `<!-- ▼ ここから header.php 相当 -->` / `<!-- ▼ ここから footer.php 相当 -->` ブロックは全ページ共通で、WP化時にそのまま切り出せます。
- `news` / `jisseki` は現行どおりカスタム投稿タイプ（エクスポートのACF定義と同名）を想定。
- 勤続年数表示は現サイトの `[tenure]` ショートコード相当を JS（`data-hire-date` 属性）で再現しています。

## デザインの方向性

- カラー: 濃紺（信頼感）× アンバー（ロゴの稲妻イエロー）。告知バーは現サイトのオリーブを継承
- フォント: Noto Sans JP
- トップ: 縦書きキャッチコピー + フェードスライダー（現サイトのコピーをそのまま使用）
- レスポンシブ対応（1040px以下でハンバーガーメニュー）、スクロールフェードイン、固定ヘッダー

## 要確認事項

- [ ] プライバシーポリシーのリンク先（フッター）— エクスポートに該当ページが見つからず、`#` のまま
- [ ] お問い合わせページ — 現サイトでは固定ページが**非公開**設定。公開時の扱いを確認（フォーム自体はCF7定義から移植済み）
- [ ] トップのスライダー画像 — 現サイトの `Group-3744.png` / `Group-3764-1.png` を使用。差し替え可
- [ ] 施工実績アーカイブのページネーション — 現サイトは8件×2ページ。静的版では全13件を1ページ表示（WP化時にページネーション復元可）

## ブログ運用について

- 記事本文のデザインは `.article__body` にまとめてあり、**`.entry-content` でも同じスタイルが効く**ようにしてあります。
  テーマ化時は `single.php` で `the_content()` を `<div class="article__body entry-content">` で包むだけで、
  エディタで書いた見出し・リスト・引用・表・画像がそのままサイトデザインで表示されます。
- 対応している装飾（Gutenberg標準ブロック対応）:
  - 見出し H2（アンバー縦バー＋グレー帯）/ H3（下線＋アンバーアクセント）/ H4（角マーク）
  - 段落・太字（マーカー風）・リンク
  - 箇条書き / 番号付きリスト（入れ子対応）
  - 引用 / テーブル / 画像（角丸＋キャプション）/ 区切り線
  - WP標準の配置クラス（aligncenter / alignleft / alignright）
- デザインの確認は **`/styleguide.html`**（装飾サンプルページ）で行えます。※本番公開時に削除

## WordPressテーマ化の手順（予定）

1. `style.css` にテーマヘッダーを追加、`functions.php` 作成（メニュー・アイキャッチ・CSS/JS読込、`news`/`jisseki` カスタム投稿タイプ登録）
2. header / footer を `header.php` / `footer.php` へ分離
3. 固定ページテンプレート作成、`front-page.php` のお知らせ・施工実績をループ出力化
4. Contact Form 7 のフォーム（お問い合わせ・求人用）を接続、reCAPTCHA設定
5. `[tenure]` ショートコード（ACF `shinozuka_hire_date` / `nakamura_hire_date`）を移植
6. パーマリンク設定を現行URLに合わせ、リダイレクト不要の移行を行う
