<template>
<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">
                Projetos
            </h1>
            <button
                @click="newProject"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition"
            >
                + Novo Projeto
            </button>
        </div>
        <div v-if="loading" class="text-center py-20">
            Carregando projetos...
        </div>
        <div v-else class="bg-white shadow rounded-xl overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">
                            Nome
                        </th>
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">
                            Descrição
                        </th>
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">
                            Status
                        </th>
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">
                            Orçamento
                        </th>
                        <th class="text-right px-6 py-3 text-sm font-semibold text-gray-600">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="project in projects"
                        :key="project.id"
                        class="border-t hover:bg-gray-50"
                    >
                        <td class="px-6 py-4 font-medium">
                            {{ project.name }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ truncate(project.description, 90) || '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="px-3 py-1 text-xs rounded-full"
                                :class="project.status === 'active'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-gray-200 text-gray-600'"
                            >
                                {{ translateProjectStatus(project.status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            $ {{ formatBudget(project.budget) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <router-link
                                    :to="`/projects/${project.id}`"
                                    class="text-blue-600 hover:underline text-sm"
                                >
                                    Ver
                                </router-link>
                                <button
                                    @click="editProject(project.id)"
                                    class="text-yellow-600 hover:underline text-sm"
                                >
                                    Editar
                                </button>
                                <button
                                    @click="deleteProject(project.id)"
                                    class="text-red-600 hover:underline text-sm"
                                >
                                    Deletar
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="projects.length === 0">
                        <td colspan="5" class="text-center py-10 text-gray-500">
                            Sem projetos encontrados
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"

const router = useRouter()

const projects = ref([])
const loading = ref(false)

const fetchProjects = async () => {
    loading.value = true

    try {
        const token = localStorage.getItem("token")

        const response = await axios.get(
            "http://localhost:8000/api/projects",
            {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        )

        projects.value = response.data
    } catch (error) {
        console.error(error)
    } finally {
        loading.value = false
    }
}

const newProject = () => {
    router.push("/projects/create")
}

const editProject = (id) => {
    router.push(`/projects/${id}/edit`)
}

const deleteProject = async (id) => {
    if (!confirm("Tem certeza que deseja deletar este projeto?")) {
        return
    }

    try {
        const token = localStorage.getItem("token")

        await axios.delete(
            `http://localhost:8000/api/projects/${id}`,
            {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        )

        projects.value = projects.value.filter(p => p.id !== id)
    } catch (error) {
        console.error(error)
    }
}

const translateProjectStatus = (status) => {
    if (status == 'active') return 'Ativo'
    if (status == 'inactive') return 'Inativo'
}

const formatBudget = (value) => {
    if (!value) return "0.00"

    return Number(value).toFixed(2)
}

const truncate = (text, length) => {
  if (!text) return ''
  return text.length > length ? text.slice(0, length) + '…' : text
}

onMounted(() => {
    fetchProjects()
})
</script>