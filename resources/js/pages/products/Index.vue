<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, usePage, router } from '@inertiajs/vue3'

// UI
import { Button } from '@/components/ui/button'
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'

// Iconos
import { Eye,Pencil, Trash2, CirclePlus } from 'lucide-vue-next'

// Props que vienen de Inertia
const { props } = usePage()

interface Product {
  id: number
  nombre: string
  descripcion?: string
  precio: number
  stock: number
  activo: boolean
}

const products = (props.products as Product[]) || []

// Breadcrumbs simples
const breadcrumbs = [
  { title: 'Productos', href: '/products' }
]

//Eliminar
const deleteProduct = async (id:number) => {
    if (!window.confirm('¿Estás seguro de que deseas eliminar este producto?')) return;

    router.delete(`/products/${id}`, {
      preserveScroll: true,
      onSuccess: () => {
          router.visit('/products', {replace: true });
      },
      onError: (errors) => {
          console.error('Error al eliminar producto:', errors);
      }
    });
}
</script>

<template>
  <Head title="Productos" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <!-- Botón crear -->
    <div class="p-4">
      <Button as-child size="sm" class="bg-indigo-500 text-white hover:bg-indigo-700">
        <Link href="/products/create">  <CirclePlus /> Crear
        </Link>
      </Button>
    </div>

    <!-- Tabla -->
    <div class="p-4">
      <Table>
        <TableCaption>Lista de Productos</TableCaption>
        <TableHeader>
          <TableRow>
            <TableHead>Nombre</TableHead>
            <TableHead>Descripción</TableHead>
            <TableHead>Precio</TableHead>
            <TableHead>Stock</TableHead>
            <TableHead>Activo</TableHead>
            <TableHead class="text-center">Acciones</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="product in products" :key="product.id">
            <TableCell>{{ product.nombre }}</TableCell>
            <TableCell>{{ product.descripcion || 'Sin descripción' }}</TableCell>
            <TableCell>{{ new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' }).format(product.precio) }}</TableCell>
            <TableCell>{{ product.stock }}</TableCell>
            <TableCell>{{ product.activo ? 'Sí' : 'No' }}</TableCell>
            <TableCell class="flex justify-center gap-2">
                <Button class="bg-blue-500 text-white hover:bg-blue-700" >
                    <Link :href="`/products/${product.id}`">
                        <Eye />
                    </Link>
                </Button>
                <Button class="bg-green-500 text-white hover:bg-green-700" >
                    <Link :href="`/products/${product.id}/edit`">
                        <Pencil />
                    </Link>
                </Button>
                <Button class="bg-rose-500 text-white hover:bg-rose-700 cursor-pointer" @click="deleteProduct(product.id)">
                  <Trash2 />
                </Button>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>
  </AppLayout>
</template>
