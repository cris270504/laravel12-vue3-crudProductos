<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router,usePage } from '@inertiajs/vue3'
import { onMounted, reactive } from 'vue'

import { Product } from '@/types'

// UI
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'

type BreadcrumbsItem = { title: string; href: string };

const {props} = usePage();
const product = props.product as Product;

const Breadcrumbs: BreadcrumbsItem[] = [
  { title: 'Productos', href: '/products' },
  { title: 'Editar', href: '#' },
];

const form = reactive<Partial<{
  nombre: string;
  descripcion: string;
  precio: number;
  stock: number;
  activo: boolean;
}>>({
  nombre: '',
  descripcion: undefined,
  precio: undefined,
  stock: 0,
  activo: true,
});

onMounted(() => {
  form.nombre = product.nombre ?? '';
  form.descripcion = product.descripcion === null ? undefined : product.descripcion;
  form.precio = product.precio ?? undefined;
  form.stock = product.stock ?? undefined;
  form.activo = product.activo ?? true;
});

const resetForm = () => {
    form.nombre = '';
    form.descripcion = undefined;
    form.precio = undefined;
    form.stock = 0;
    form.activo = true;
};

//Método para enviar el formulario
const submit = () => {
  router.put(`/products/${product.id}`, form, {onSuccess: resetForm});
};
</script>

<template>
    <Head title="Editar producto" />
    <AppLayout :breadcrumbs="Breadcrumbs">
        <div class="flex flex-l flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">Editar</h1>
            <form @submit.prevent="submit" class="space-y-6 max-w-lg">
                <div 
                  v-for="(label, key) in {
                    nombre: 'Nombre',
                    descripcion: 'Descripción',
                    precio: 'Precio',
                    stock: 'Stock',
                    activo: 'Activo'
                  }" 
                  :key="key" 
                  class="space-y-2"
                >
                  <label :for="key">{{ label }}</label>

                  <template v-if="key === 'activo'">
                    <select
                      id="activo"
                      v-model="form.activo"
                      class="border rounded px-2 py-1 w-full"
                    >
                      <option :value="true" class="text-black">Sí</option>
                      <option :value="false" class="text-black">No</option>
                    </select>
                  </template>

                  <template v-else>
                    <Input
                      :id="key"
                      v-model="form[key]"
                      :type="key === 'precio' || key === 'stock' ? 'number' : 'text'"
                      :step="key === 'precio' ? '0.01' : key === 'stock' ? '1' : undefined"
                      :placeholder="label"
                      :required="key !== 'descripcion'"
                    />
                  </template>
                </div>

                <div class="flex gap-4">
                    <Button type="submit" class="bg-indigo-500 hover:bg-indigo-600">Guardar</Button>
                    <Button as="a" href="/products" variant="outline">Cancelar</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>