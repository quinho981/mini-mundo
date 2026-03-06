<template>
<div class="min-h-screen grid md:grid-cols-2">
    <Toast />

    <div class="hidden md:flex flex-col justify-center items-center bg-green-600 text-white p-12">
        <h1 class="text-4xl font-bold mb-4">
            Crie sua conta
        </h1>
        <p class="text-lg text-green-100 text-center max-w-md">
            Comece a organizar seus projetos e gerenciar tarefas de forma simples e eficiente.
        </p>
    </div>
    <div class="flex items-center justify-center bg-gray-50 p-6">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-center">
                Registrar
            </h2>
            <form @submit.prevent="handleRegister" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Nome
                    </label>
                    <input
                        v-model="name"
                        type="text"
                        placeholder="Your name"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        required
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Email
                    </label>
                    <input
                        v-model="email"
                        type="email"
                        placeholder="email@email.com"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        required
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Senha
                    </label>
                    <input
                        v-model="password"
                        type="password"
                        placeholder="********"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        required
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Confirmar senha
                    </label>
                    <input
                        v-model="password_confirmation"
                        type="password"
                        placeholder="********"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        required
                    />
                    <p v-if="error" class="text-red-500 text-sm">
                        {{ error }}
                    </p>
                </div>
                <button
                    :disabled="loading"
                    class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2 disabled:opacity-50"
                >
                    <span v-if="loading" class="animate-spin border-2 border-white border-t-transparent rounded-full w-4 h-4"></span>

                    {{ loading ? 'Creating account...' : 'Create account' }}
                </button>
            </form>
            <p class="text-sm text-center mt-4">
                Já possui uma conta?
                <router-link
                    to="/auth/login"
                    class="text-blue-600 font-medium hover:underline"
                >
                    Login
                </router-link>
            </p>
        </div>
    </div>
</div>
</template>

<script setup>
import { ref } from "vue"
import { useRouter } from "vue-router"
import { useToast } from "primevue/usetoast"
import { register } from "@/services/authService"

const router = useRouter()
const toast = useToast()

const name = ref("")
const email = ref("")
const password = ref("")
const password_confirmation = ref("")
const error = ref(null)
const loading = ref(false)

const handleRegister = async () => {
    error.value = null
    loading.value = true

    try {
        await register({
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value
        })

        toast.add({
            severity: "success",
            summary: "Success",
            detail: "Conta criada com sucesso!",
            life: 3000
        })

        router.push("/auth/login")
    } catch (err) {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: "Registro falhou!",
            life: 3000
        })

        error.value = "Registro falhou!"
    } finally {
        loading.value = false
    }
}
</script>