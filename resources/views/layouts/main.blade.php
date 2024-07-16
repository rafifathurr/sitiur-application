<!DOCTYPE html>
<html lang="en">
  @include('layouts.head')
  <body>
   @include('layouts.header')
    <!-- az-header -->

   @yield('content')
    <!-- az-content -->

    @include('layouts.footer')
    <!-- az-footer -->

    @include('layouts.script')
   <!-- script -->
  </body>
</html>
