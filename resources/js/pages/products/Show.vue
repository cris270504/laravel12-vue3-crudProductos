<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import type { Product } from '@/types'

// UI
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

type BreadcrumbsItem = { title: string; href: string }

const page = usePage()
const product = computed(() => (page.props.product ?? {}) as Partial<Product>)

const Breadcrumbs: BreadcrumbsItem[] = [
  { title: 'Productos', href: '/products' },
  { title: 'Ver', href: '#' },
]

// Devuelve SIEMPRE string
const displayValue = (key: string): string => {
  const p = product.value
  if (!p) return ''
  if (key === 'precio') return p.precio != null
    ? new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' })
        .format(Number(p.precio))
    : ''
  if (key === 'stock') return p.stock != null ? String(p.stock) : ''
  if (key === 'activo') return typeof p.activo === 'boolean' ? (p.activo ? 'Sí' : 'No') : ''
  return String((p as any)[key] ?? '')
}
</script>

<template>
  <Head title="Ver producto" />
  <AppLayout :breadcrumbs="Breadcrumbs">
    <div class="flex flex-col gap-4 rounded-xl p-4">
      <h1 class="text-2xl font-bold">Detalle</h1>

      <div class="space-y-6 max-w-lg">
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
          <Label :for="key">{{ label }}</Label>
          <Input
            :id="key"
            :model-value="displayValue(key as string)"
            type="text"
            readonly
          />
        </div>

        <div class="flex gap-4">
          <Button as-child variant="outline">
            <a href="/products">Volver</a>
          </Button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
