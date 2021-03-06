syntax on
filetype off
" format and user interface
set nocompatible
set number
set softtabstop=4 tabstop=4 shiftwidth=4
set cindent
set showmatch
set history=50
set expandtab
set hlsearch
set mouse=a 
set cursorline
set cursorcolumn
""set statusline=%F%m%r%h%w\[POS=%l,%v][%p%%]\%{strftime(\"%d/%m/%y\ -\ %H:%M\"){}""}
set laststatus=2
""set statusline=%F%m%r%h%w\ [FORMAT=%{&ff{]\ [TYPE=%Y]\ [ASCII=\%03.3b]\ [HEX=\%02.2B]\ [POS=%04l,%04v][%p%%]\ [LEN=%L]}}
set backspace=2
set fdm=indent
set fillchars=vert:\ ,stl:\ ,stlnc:\
set noerrorbells
set smarttab
set nobackup
filetype on
" initialize vundle
if !isdirectory(expand("~/.vim/bundle/vundle/.git"))
    !git clone https://github.com/gmarik/vundle ~/.vim/bundle/vundle
endif
set rtp+=~/.vim/bundle/vundle/
call vundle#rc()

" feature bundles
Bundle 'gmarik/vundle'
Bundle 'scrooloose/nerdtree'
Bundle 'scrooloose/nerdcommenter'
Bundle 'jistr/vim-nerdtree-tabs'
Bundle 'scrooloose/syntastic'
Bundle 'SuperTab'
Bundle 'taglist.vim'
Bundle 'ctags'
Bundle 'winmanager'
Bundle 'kien/ctrlp.vim'
Bundle 'gregsexton/MatchTag'
Bundle 'bronson/vim-trailing-whitespace'
Bundle 'marijnh/tern_for_vim'''
Bundle 'majutsushi/tagbar'
Bundle 'Lokaltog/vim-powerline'
"Bundle 'ervandew/supertab'
"Bundle 'tpope/vim-fugitive'
"Bundle 'tpope/vim-surround'
"Bundle 'scrooloose/syntastic'
Bundle 'bling/vim-airline'
Bundle 'Valloric/YouCompleteMe'
Bundle 'DoxygenToolkit.vim'
"Bundle 'editorconfig/editorconfig-vim'
"Bundle 'mattn/emmet-vim'
"" file type bundles
"Bundle 'kchmck/vim-coffee-script'
"Bundle 'derekwyatt/vim-scala'
"Bundle 'Glench/Vim-Jinja2-Syntax'
"Bundle 'sophacles/vim-bundle-mako'
"Bundle 'othree/html5.vim'
"Bundle 'sprsquish/thrift.vim'
"Bundle 'tonyseek/rust.vim'
"Bundle 'cakebaker/scss-syntax.vim'
"Bundle 'wavded/vim-stylus'
"Bundle 'jansenm/vim-cmake'
":Bundle 'vim-ruby/vim-ruby'
"Bundle 'tfnico/vim-gradle'
"Bundle 'cespare/vim-toml'
"Bundle 'evanmiller/nginx-vim-syntax'
" theme bundles
Bundle 'shawncplus/phpcomplete.vim'
"Bundle 'tomasr/molokai'

" key mapping
let mapleader=" "
imap jk <ESC>
imap Jk <ESC>
imap JK <ESC>
imap jK <ESC>
""nmap 1 ^
""nmap 0 $
nmap <Leader>tb :TagbarToggle<CR>
nmap <Leader>wm :WMToggle<CR>
nmap <Leader>tt :NERDTreeToggle<CR>
nmap <Leader>nt :tabnew<CR>
nmap <Leader>j <C-W>j<C-W>
nmap <Leader>c :close<CR>
nmap <Leader>q :wqall<CR>
nmap <Leader>n :vsp<CR>
nmap <Leader>g :!git 
""nmap <TAB> :close<CR>
nmap <C-J> <C-W>j<C-W>_
nmap <C-K> <C-W>k<C-W>_
nmap <C-L> <C-W>l<C-W>_
nmap <C-H> <C-W>h<C-W>_
nmap ;ntr :NERDTree<CR>
inoremap { {<CR><CR>}<Esc>i
"inoremap } {}<Esc>i
inoremap " ""<Esc>i
inoremap ' ''<Esc>i
inoremap ff <C-x><C-o>

" custom commands
com! FormatJSON %!python -m json.tool

" configurations of plugins
let g:nerdtree_tabs_open_on_console_startup = 0
let g:nerdtree_tabs_open_on_gui_startup = 0
let g:airline_powerline_fonts = 1
""let g:airline_theme = 'jellybeans'
let NERDTreeIgnore = ['\.py[oc]$', '__pycache__', '\.egg-info']
let g:ctrlp_custom_ignore = 'node_modules\|pyc\|git\|__pycache__'
let g:tagbar_ctags_bin='/usr/bin/ctags'
let g:ctrlp_use_caching = 1
let g:ctrlp_working_path_mode = 0
let g:syntastic_cpp_compiler_options = ' -std=c++11'
let g:syntastic_java_javac_config_file_enabled = 1
let g:loaded_syntastic_rst_rst2pseudoxml_checker = 1
let g:jinja_syntax_html = 1
if filereadable('/usr/bin/ctags')
    let g:tagbar_ctags_bin = '/usr/bin/ctags'
endif
let g:Tlist_Ctags_Cmd='/usr/bin/ctags'
let Tlist_Use_Right_Window = 0
let Tlist_Compart_Format = 1
let Tlist_Exist_OnlyWindow = 1
let Tlist_File_Fold_Auto_Close = 0
""let Tlist_Show_Menu=1
""let Tlist_Auto_Open=1

let g:NERDTree_title='[NERDTree]'
function! NERDTree_Start()
    exec 'NERDTree'
endfunction
function! NERDTree_IsValid()
    return 1
endfunction''
let g:winManagerWindowLayout = 'NERDTree|TagList'
let g:winManagerWidth = 30
let g:AutoOpenWinManager = 1
function! FiletypeHook(config)
    if has_key(a:config, 'language')
        let &filetype = a:config['language']
    endif
    return 0   " Return 0 to show no error happened
endfunction
"call editorconfdNewHook(function('FiletypeHook'))
colorscheme antares
" display style
if exists('+colorcolumn')
    ""set colorcolumn=80
endif
if &term == 'linux' || &term == 'ansi'
    set t_Co=8
else
    set t_Co=256
endif
""let base16colorspace=256
set background=dark

let g:SuperTabRetainCompletionType = 2
let g:SuperTabDefaultCompletionType = "<C-X><C-N>" 
let g:SuperTabDefaultCompletionType="context"

"DoxygenToolkit
let g:DoxygenToolkit_authorName="jackie <zhangjie@tvmining.com>"
let g:DoxygenToolkit_briefTag_funcName="yes"
let g:doxygen_enhanced_color=1

" indent
autocmd Filetype ruby setlocal ts=2 sts=2 sw=2
autocmd Filetype coffee setlocal ts=2 sts=2 sw=2
autocmd Filetype stylus setlocal ts=2 sts=2 sw=2
autocmd FileType php set omnifunc=phpcomplete
""autocmd BufReadPost *  if line("'\"") > 0 && line("'\"") <= line("$") | / exe "normal g`\"" |endif"""""""'"""'"
if has("autocmd")
    autocmd BufReadPost *
                \ if line("'\"") > 0 && line("'\"") <= line("$") |
                \   exe "normal g`\"" |
                \ endif
endif"""""""'"""'"""
""au BufReadPost * if line("'\"") > 1 && line("'\"") <= line("$") | exe "normal g'\"" | endif""'"""""'"""'"
""autocmd BufReadPost * / if line("'/"") > 0 && line("'/"") <= line("$") | / exe "normal g`/"" | / endif"""""""'"""'"
""au BufReadPost * if line("'\"") > 0|if line("'\"") <= line("$")|exe("norm '\"")|else|exe "norm $"|endif|endif""""'"""""'"""'"
filetype plugin on
filetype indent on
set guifont=Courier\ New:h10
