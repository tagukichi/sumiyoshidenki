#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
WordPressテーマ（wp-theme/sumiyoshi-denki）の仕上げ用スクリプト

1. 静的サイトと共通の assets/css/style.css・assets/js/main.js をテーマへコピー
2. 記事本文のスタイルからブロックエディタ用の editor-style.css を生成
   （エディタ上でも公開ページと同じ見出し・リスト・表の見た目で書けるようにする）
3. dist/sumiyoshi-denki.zip を作成（管理画面の「テーマのアップロード」用）

使い方:  python3 tools/build_theme.py   （リポジトリルートで実行）
CSS/JS を編集したら、静的サイトの tools/build.py と合わせてこのスクリプトも実行してください。
"""
import os
import re
import shutil
import zipfile

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
THEME_SLUG = "sumiyoshi-denki"
THEME = os.path.join(ROOT, "wp-theme", THEME_SLUG)
DIST = os.path.join(ROOT, "dist")

BODY_PREFIX = re.compile(r"^\s*\.(?:article__body|entry-content)\b\s*")


def copy_assets():
    for rel in ("assets/css/style.css", "assets/js/main.js"):
        dst = os.path.join(THEME, rel)
        os.makedirs(os.path.dirname(dst), exist_ok=True)
        shutil.copyfile(os.path.join(ROOT, rel), dst)


def section(css, start_marker, end_marker):
    start = css.index(start_marker)
    end = css.index(end_marker, start)
    # start_marker を含むコメントの先頭から
    start = css.rfind("/*", 0, start)
    return css[start:end]


def to_editor_selector(selector):
    """'.article__body h2' → 'h2' / '.entry-content' → 'body'（エディタ側で .editor-styles-wrapper に変換される）"""
    sel = selector.strip()
    if sel in (".article__body", ".entry-content"):
        return "body"
    if BODY_PREFIX.match(sel):
        rest = BODY_PREFIX.sub("", sel)
        return "body " + rest if rest.startswith(">") else rest
    return sel


def rewrite_selectors(css):
    def repl(m):
        selectors = [to_editor_selector(s) for s in m.group(1).split(",")]
        unique = list(dict.fromkeys(s for s in selectors if s))
        return ", ".join(unique) + " {"

    css = re.sub(r"/\*.*?\*/", "", css, flags=re.S)  # コメントはセレクタ判定の邪魔になるので除去
    css = re.sub(r"\n{3,}", "\n\n", css)
    # セレクタ部分（'{' の直前、@media 行は除く）を書き換える
    return re.sub(r"(?m)^(?!\s*@)([^{}\n][^{}]*?)\s*\{", repl, css)


def build_editor_style():
    css = open(os.path.join(ROOT, "assets/css/style.css"), encoding="utf-8").read()
    root_vars = re.search(r":root\s*\{[^}]*\}", css).group(0)
    typography = section(css, "記事本文タイポグラフィ", ".map-embed {")
    palette = section(css, "エディタの色パレット", "Contact Form 7")
    out = [
        "/* このファイルは tools/build_theme.py が assets/css/style.css から自動生成します。直接編集しないでください。 */",
        root_vars,
        'body { font-family: var(--font-base); color: var(--color-text); font-feature-settings: "palt"; }',
        ".editor-post-title__input, .wp-block-post-title { font-family: var(--font-base); font-weight: 700; color: var(--color-primary); }",
        rewrite_selectors(typography),
        rewrite_selectors(palette),
    ]
    dst = os.path.join(THEME, "assets/css/editor-style.css")
    with open(dst, "w", encoding="utf-8") as f:
        f.write("\n\n".join(out).rstrip() + "\n")


def build_zip():
    os.makedirs(DIST, exist_ok=True)
    zip_path = os.path.join(DIST, THEME_SLUG + ".zip")
    with zipfile.ZipFile(zip_path, "w", zipfile.ZIP_DEFLATED) as z:
        for base, _dirs, files in os.walk(THEME):
            for name in sorted(files):
                if name.startswith("."):
                    continue
                full = os.path.join(base, name)
                arc = os.path.join(THEME_SLUG, os.path.relpath(full, THEME))
                z.write(full, arc)
    return zip_path


if __name__ == "__main__":
    copy_assets()
    build_editor_style()
    path = build_zip()
    print("theme assets synced / editor-style.css generated")
    print("zip:", os.path.relpath(path, ROOT))
