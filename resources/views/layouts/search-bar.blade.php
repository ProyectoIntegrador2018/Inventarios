<!DOCTYPE html>
@section('searchBar')
<!-- Barra de búsqueda -->
<div class="col-md-12 mb-5 input-group align-items-center">
  <label for="txb_search" class="p-0 px-2 m-0">Buscar dispositivo</label>
  <input type="text" class="form-control" id="txb_search" placeholder="#movil #dron #celular #tableta etc..." value="" required="">
  <div class="input-group-prepend align-self-stretch">
    <span class="input-group-text" id="basic-addon1">
      <i class="fas fa-search"></i>
    </span>
  </div>
</div>
@show

@section('script')
  <script type="text/javascript">
    $(document).ready(function(){

      $('#txb_search').keyup(function(e){
        if(e.keyCode == 13) {
          searchDevice()
        }
      })

      function searchDevice() {
        var searchBar = $('#txb_search');
        var inputs = fetchInput(searchBar.val())
        var url = route('device.search')+ "?" + $.param(inputs);
        console.log(inputs);
        if(inputs.tagsQuantity != 0 || inputs.word != "null") {
          window.location = url;
        }
      }

      function fetchInput(search) {
        var allTags = getTags(search);
        var device = "";

        if (allTags.length == 0) {
          device = getDeviceName(search);
        }

        return {_token    : $('meta[name="csrf-token"]').attr('content'),
                tags          : allTags,
                tagsQuantity  : allTags.length,
                word          : device}
      }

      function getTags(search) {
        var tags = search.match(/#(\w+)/g)
        if (tags == null) {
          return new Array()
        } else {
          return cleanedTags(tags)
        }
      }

      function getDeviceName(search) {
        var device = search.match(/\w+(\s*\w+)*/g)
        return cleanedDeviceName(device)
      }

      function cleanedTags(tags) {
        for (i = 0; i < tags.length; i++) {
          tags[i] = tags[i].substr(1);
        }
        return tags;
      }

      function cleanedDeviceName(device) {
        return String(device).replace(/\s+/g,' ')
      }
    });
  </script>
@endsection
