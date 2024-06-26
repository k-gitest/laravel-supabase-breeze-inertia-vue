<script setup lang="ts">
  import { supabaseURL, supabaseNoImage } from "@/lib/supabase"
  import { Link, router } from "@inertiajs/vue3";
  import type { Image } from "@/types/image";
  import { isoDateGenerator } from "@/lib/isoDateGenerator";

  defineProps<{
    image?: Image[];
    name: string;
    id: number;
    description: string;
    price_excluding_tax: string;
    price_including_tax: string;
    category_name?: string;
    created_at: string;
    route_show: string;
    route_edit?: string;
    route_destroy?: string;
    mode?: string;
    count?: number;
    stock?: number | null;
    delete_id?: number;
  }>();

  const emit = defineEmits<{
    addFavorite: [id: number]
  }>()

  const handleFavorite = (id: number) => {
    emit('addFavorite', id); 
  }
  
  const deleteFavorite = (id: number) => {
    router.delete(`/favorite/${id}`,{
       preserveState: false,
    })
  }
</script>

<template>
  <div class="card bg-base-100 shadow-xl relative">
    <div v-if="created_at >= isoDateGenerator()">
      <span class="absolute top-0 end-0 inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium transform -translate-y-1/2 translate-x-1/2 bg-red-500 text-white z-10">new</span>
      <!--
      <span class="absolute top-0 start-0 inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium transform -translate-x-1/2 -translate-y-1/2 bg-red-500 text-white z-10">new</span>
      -->
    </div>
    <Link :href="route(route_show, {id: id})" class="flex flex-col h-full justify-between rounded-lg">
      <figure v-if="image && image.length" class="h-56">
        <img :src="supabaseURL + image[0].path" />
      </figure>
      <figure v-else class="h-56">
        <img :src="supabaseURL + supabaseNoImage" class="rounded-lg" />
      </figure>
      <div class="card-body p-2 justify-end">
        <h2 class="card-title">
          {{ name }}
        </h2>
        <p>{{ description }}</p>
        <p>{{ price_excluding_tax }}</p>
        <p>{{ price_including_tax }}</p>
        <div class="card-actions justify-end">
          <div class="badge badge-outline text-xs">{{ category_name }}</div>
        </div>
        <div v-if="stock && stock > 0 && stock < 5">
          在庫数：残り僅か
        </div>
        <div v-else-if="stock === 0 || !stock">
          <p class="text-red-400">売り切れ</p>
        </div>
        <button v-if="mode === 'favorite.enable'" @click.stop.prevent="handleFavorite(id)" class="btn btn-sm">お気に入りに追加<span class="badge">{{ count }}</span></button>
        <button v-if="mode === 'favorite.disable'" class="btn btn-sm" disabled>お気に入りに追加<span class="badge">{{ count }}</span></button>
        <button v-if="mode === 'favorite.delete'" @click.stop.prevent="deleteFavorite(delete_id ?? id)" class="btn btn-sm">削除</button>
      </div>
    </Link>
  </div>
</template>