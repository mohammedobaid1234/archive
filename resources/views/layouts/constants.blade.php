<script>
    @php
        $constants = (new \App\Constants)->index();

        foreach($constants as $constant => $value){
            echo "const $constant = $value;\n"; 
        }
    @endphp
</script>