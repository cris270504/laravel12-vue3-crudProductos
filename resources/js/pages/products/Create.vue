<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router, usePage} from '@inertiajs/vue3'
import { ref, computed  } from 'vue'

// UI
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'

// Breadcrumbs
type BreadcrumbsItem = { title: string; href: string };
const breadcrumbs: BreadcrumbsItem[] = [
  { title: 'Productos', href: '/products' },
  { title: 'Crear', href: '#' },
];

const form = ref<Partial<{
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

// errores de validación
const page = usePage()
const errors = computed(() => (page.props.errors ?? {}) as Record<string, string>)

const resetForm = () => {
  form.value = {
    nombre: '',
    descripcion: undefined,
    precio: undefined,
    stock: 0,
    activo: true,
  };
};  

const submit = () => {
    router.post('/products', form.value, {onSuccess: resetForm});
}
</script>

<template>
    <Head title="Crear producto" />
    <AppLayout :breadcrumbs="breadcrumbs">
      <div class="flex flex-l flex-col gap-4 rounded-xl p-4">
        <h1 class="text-2xl font-bold">Crear</h1>
          <form @submit.prevent="submit" class="space-y-6 max-w-lg">
            <div
              v-for="(label, key) in {nombre: 'Nombre', descripcion: 'Descripción', precio: 'Precio', stock: 'Stock', activo: 'Activo'}"
              :key="key"
              class="space-y-2"
            >
              <label :for="key">{{ label }}</label>

              <template v-if="key === 'activo'">
                <select id="activo" v-model="form.activo" class="border rounded px-2 py-1 w-full">
                  <option :value="true" class="text-black">Sí</option>
                  <option :value="false" class="text-black">No</option>
                </select>
              </template>

              <template v-else>
                <Input
                  :id="key"
                  v-model="(form as any)[key]"
                  :type="key === 'precio' || key === 'stock' ? 'number' : 'text'"
                  :step="key === 'precio' ? '0.01' : key === 'stock' ? '1' : undefined"
                  :placeholder="label"
                    :required="key !== 'descripcion'"
                    inputmode="numeric"
                  />
                </template>

                <p v-if="errors[key as string]" class="text-sm text-red-600">
                  {{ errors[key as string] }}
                </p>
      </div>

                <div class="flex gap-4">
                    <Button type="submit" class="bg-indigo-500 hover:bg-indigo-600">Guardar</Button>
                    <Button as="a" href="/products" variant="outline">Cancelar</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>