<template>
<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">
                    {{ project?.name }}
                </h1>
                <p class="text-gray-500 mt-1">
                    {{ project?.description || "Sem descrição" }}
                </p>
            </div>
            <div class="flex gap-3">
                <button
                    @click="$router.push('/projects')"
                    class="bg-neutral-500 text-white px-4 py-2 rounded-lg hover:bg-neutral-600"
                >
                    Voltar
                </button>
                <button
                    @click="editProject"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                >
                    Editar
                </button>
                <button
                    @click="deleteProject"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700"
                >
                    Deletar
                </button>
                <button
                    @click="logout"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700"
                >
                    <i class="pi pi-sign-out"></i>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">
                    Status
                </p>
                <p class="text-lg font-semibold mt-1">
                    {{ translateProjectStatus(project?.status) }}
                </p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">
                    Orçamento
                </p>
                <p class="text-lg font-semibold mt-1">
                    R$ {{ formatBudget(project?.budget) }}
                </p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow">
                <p class="text-sm text-gray-500">
                    Tarefas
                </p>
                <p class="text-lg font-semibold mt-1">
                    {{ tasks.length }}
                </p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-semibold">
                    Tarefas
                </h2>
                <button
                    @click="openCreateTask"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700"
                >
                    + Nova tarefa
                </button>
            </div>
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Descrição
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Predecessora
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Data início
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Data fim
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="task in tasks"
                        :key="task.id"
                        class="border-t hover:bg-gray-50"
                    >
                        <td class="px-6 py-4">
                            {{ task.description }}
                        </td>
                        <td class="px-6 py-4">
                            {{ translateTaskStatus(task.status) }}
                        </td>
                        <td class="px-6 py-4">
                            <span v-if="task.task_id">
                                {{ getPredecessor(task.task_id)?.description || '-' }}
                            </span>
                            <span v-else class="text-gray-400">
                                -
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ formatDateBR(task.start_date) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ formatDateBR(task.end_date) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-left gap-2">
                                <button
                                    @click="openViewTask(task)"
                                    class="text-blue-600 hover:underline text-sm"
                                >
                                    Ver
                                </button>
                                <button
                                    @click="openEditTask(task)"
                                    class="text-yellow-600 hover:underline text-sm"
                                >
                                    Editar
                                </button>
                                <button
                                    @click="deleteTask(task.id)"
                                    class="text-red-600 hover:underline text-sm"
                                >
                                    Deletar
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="tasks.length === 0">
                        <td colspan="4" class="text-center py-10 text-gray-500">
                            Sem tarefas ainda
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div
        v-if="showTaskModal"
        class="fixed inset-0 backdrop-blur-sm bg-opacity-40 flex items-center justify-center"
    >
        <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">
                <span v-if="taskMode === 'create'">Nova tarefa</span>
                <span v-if="taskMode === 'edit'">Editar tarefa</span>
                <span v-if="taskMode === 'view'">Detalhes da tarefa</span>

            </h2>
            <div class="space-y-4">
                <div>
                    <label class="text-sm text-gray-600">Descrição</label>
                    <input
                        v-model="taskForm.description"
                        :disabled="taskMode === 'view'"
                        class="w-full border rounded-lg px-3 py-2"
                    />
                </div>
                <div>
                    <label class="text-sm text-gray-600">Status</label>
                    <select
                        v-model="taskForm.status"
                        :disabled="taskMode === 'view'"
                        class="w-full border rounded-lg px-3 py-2"
                    >
                        <option value="not_completed">
                            Não concluído
                        </option>
                        <option value="completed">
                            Concluído
                        </option>
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Tarefa predecessora</label>
                    <select
                        v-model="taskForm.task_id"
                        :disabled="taskMode === 'view'"
                        class="w-full border rounded-lg px-3 py-2"
                    >
                        <option :value="null">Nenhuma</option>
                        <option
                            v-for="task in availablePredecessors"
                            :key="task.id"
                            :value="task.id"
                        >
                            {{ task.description }}
                        </option>
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Data início</label>
                    <input
                        type="date"
                        v-model="taskForm.start_date"
                        :disabled="taskMode === 'view'"
                        class="w-full border rounded-lg px-3 py-2"
                    />
                </div>
                <div>
                    <label class="text-sm text-gray-600">Data fim</label>
                    <input
                        type="date"
                        v-model="taskForm.end_date"
                        :disabled="taskMode === 'view'"
                        class="w-full border rounded-lg px-3 py-2"
                    />
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button
                    @click="closeTaskModal"
                    class="px-4 py-2 bg-gray-200 rounded-lg"
                >
                    Cancelar
                </button>
                <button
                    v-if="taskMode !== 'view'"
                    @click="saveTask"
                    :disabled="savingTask"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center gap-2 disabled:opacity-50"
                >
                    <span v-if="savingTask">
                        Salvando...
                    </span>
                    <span v-else>
                        Salvar
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue"
import { useRoute, useRouter } from "vue-router"
import axios from "axios"

const route = useRoute()
const router = useRouter()

const project = ref(null)
const tasks = ref([])
const loading = ref(false)
const showTaskModal = ref(false)
const taskMode = ref("create")
const savingTask = ref(false)

const taskForm = ref({
    id: null,
    description: "",
    status: "not_completed",
    start_date: "",
    end_date: "",
    task_id: null
})

const availablePredecessors = computed(() => {
    return tasks.value.filter(task => task.id !== taskForm.value.id)
})

const getPredecessor = (taskId) => {
    return tasks.value.find(t => t.id === taskId)
}

const openCreateTask = () => {
    taskMode.value = "create"

    taskForm.value = {
        id: null,
        description: "",
        status: "not_completed",
        start_date: "",
        end_date: "",
        task_id: null
    }

    showTaskModal.value = true
}

const openViewTask = (task) => {
    taskMode.value = "view"
    taskForm.value = normalizeTaskDates(task)
    showTaskModal.value = true
}

const openEditTask = (task) => {
    taskMode.value = "edit"
    taskForm.value = normalizeTaskDates(task)
    showTaskModal.value = true
}

const closeTaskModal = () => {
    showTaskModal.value = false
}

const normalizeTaskDates = (task) => ({
    ...task,
    start_date: task.start_date?.split("T")[0] || null,
    end_date: task.end_date?.split("T")[0] || null,
    task_id: task.task_id ?? null
})

const saveTask = async () => {
    savingTask.value = true
    
    try {
        const token = localStorage.getItem("token")

        if (taskMode.value === "create") {
            await axios.post(
                `http://localhost:8000/api/projects/${route.params.id}/task`,
                taskForm.value,
                {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                }
            )
        }

        if (taskMode.value === "edit") {
            await axios.patch(
                `http://localhost:8000/api/tasks/${taskForm.value.id}`,
                taskForm.value,
                {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                }
            )
        }

        await fetchTasks()

        showTaskModal.value = false
    } catch (error) {
        alert(error.response?.data?.message || "Erro! Tente novamente.")
    } finally {
        savingTask.value = false
    }
}

const fetchProject = async () => {
    loading.value = true

    try {
        const token = localStorage.getItem("token")

        const response = await axios.get(
            `http://localhost:8000/api/projects/${route.params.id}`,
            {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        )

        project.value = response.data
    } catch (error) {
            alert(error.response?.data?.message || "Erro ao buscar o projeto, reinicie a página.")
    } finally {
        loading.value = false
    }
}

const fetchTasks = async () => {
    loading.value = true

    try {
        const token = localStorage.getItem("token")

        const response = await axios.get(
            `http://localhost:8000/api/projects/${route.params.id}/tasks`,
            {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        )

        tasks.value = response.data ?? []
    } catch (error) {
        alert(error.response?.data?.message || "Erro ao buscar as tarefas, reinicie a página.")
    } finally {
        loading.value = false
    }
}

const deleteTask = async (id) => {
    if (!confirm("Tem certeza que deseja excluir essa tarefa?")) return

    try {
        const token = localStorage.getItem("token")

        await axios.delete(
            `http://localhost:8000/api/tasks/${id}`,
            {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        )

        fetchTasks()
    } catch (error) {
        alert(error.response?.data?.message || "Erro ao deletar tarefa")
    }
}

const logout = async () => {
    if (!confirm("Deseja sair do sistema?")) return

    try {
        const token = localStorage.getItem("token")

        await axios.post(
            `http://localhost:8000/api/auth/logout`,
            {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        )
    } catch (error) {
        alert(error.response?.data?.message || "Erro. Tente novamente")
    } finally {
        localStorage.removeItem("token")
        router.push("/auth/login")
    }
}

const formatBudget = (value) => {
    if (!value) return "0.00"

    return Number(value).toFixed(2)
}

const translateProjectStatus = (status) => {
    if (status == 'active') return 'Ativo'
    if (status == 'inactive') return 'Inativo'
}

const translateTaskStatus = (status) => {
    if (status == 'completed') return 'Concluído'
    if (status == 'not_completed') return 'Não concluído'
}

const formatDateBR = (dateString) => {
    if(!dateString) return '-';

    const date = new Date(dateString);

    return date.toLocaleDateString('pt-BR', {
        timeZone: 'UTC'
    });
}

onMounted(() => {
    fetchProject()
    fetchTasks()
})
</script>