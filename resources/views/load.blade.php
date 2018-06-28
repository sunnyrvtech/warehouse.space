@push('scripts')
<script type="text/javascript">
    alert('hello');
window.top.location = "{{ $redirect_url }}";
</script>
@endpush
