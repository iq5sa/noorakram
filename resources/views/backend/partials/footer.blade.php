<footer class="footer py-4">
    <div class="container-fluid">
        <div class="d-block mx-auto">
            {!! !is_null($footerTextInfo) ? $footerTextInfo->copyright_text : '' !!}
        </div>
    </div>
</footer>
