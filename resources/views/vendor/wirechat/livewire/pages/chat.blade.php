<div class="w-full flex min-h-full h-full rounded-lg" >
    <!-- Sidebar -->
    <div class="hidden md:grid bg-inherit border-r border-[var(--wc-light-border)] dark:border-[var(--wc-dark-border)]   dark:bg-inherit  relative w-full h-[95vh] md:w-[360px] lg:w-[400px] xl:w-[500px]  shrink-0 overflow-y-auto  ">
       <livewire:wirechat.chats/> 
    </div>
    <!-- Message content -->
    <main  class="grid  w-full  grow  h-[95vh] min-h-min relative overflow-y-auto"  style="contain:content">
      <livewire:wirechat.chat  conversation="{{$this->conversation->id}}"/>
    </main>

</div>