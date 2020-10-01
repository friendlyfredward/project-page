# Simple Project Page
A basic website to offer information and download links to open-source projects. I originally made this for [MCinaBox](https://github.com/AOF-Dev/MCinaBox) so that people who weren't familiar with how GitHub releases work (surprisingly far too many) could easily download the app.

The project files here are the same as what is hosted on the [MCinaBox download site](https://mcinabox.fredward.xyz/), but I decided to share the source here in case anyone was curious or wanted to modify it to use for their own project. It's very basic, quickly made, but easy to modify.

The main site is written in HTML using [Bootstrap](https://getbootstrap.com/) (loaded from CDN) for easy theming. The actual downloads page is basic PHP, pulling from a [published Google Sheets file](https://docs.google.com/spreadsheets/d/e/2PACX-1vS0ka65eWKugM_Ev_AQA1Whcg4_cAXiMmOcC5RWbVTROXhyC1eVMVas83OKObzbgbzjgQ9_NaiCrhnh/pubhtml) so it's easy to add download links when a new version comes out. The actual script is pretty slow because it has to request info from the external source, so I use cloudflare caching (which is free) to speed up page load times.
