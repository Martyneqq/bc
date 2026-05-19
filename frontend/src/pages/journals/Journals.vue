<script setup lang="ts">
import { ref } from 'vue'
import JournalViewer from '../../components/JournalViewer.vue'

const activeJournal = ref<'general-ledger' | 'cash' | 'bank' | 'income' | 'expense' | 'demand-debt'>(
  'general-ledger',
)

const journals = [
  {
    id: 'general-ledger',
    name: 'General Ledger',
    description: 'Complete record of all transactions',
    icon: '📊',
  },
  {
    id: 'cash',
    name: 'Cash Journal',
    description: 'All cash transactions',
    icon: '💵',
  },
  {
    id: 'bank',
    name: 'Bank Journal',
    description: 'All bank transfer transactions',
    icon: '🏦',
  },
  {
    id: 'income',
    name: 'Income Journal',
    description: 'All income records',
    icon: '📈',
  },
  {
    id: 'expense',
    name: 'Expense Journal',
    description: 'All expense records',
    icon: '📉',
  },
  {
    id: 'demand-debt',
    name: 'Demand/Debt Journal',
    description: 'Receivables and payables',
    icon: '📋',
  },
]
</script>

<template>
  <div class="journals-page">
    <div class="page-header">
      <h1>Accounting Journals</h1>
      <p>View detailed journals and ledgers for your financial records</p>
    </div>

    <div class="journal-selector">
      <button
        v-for="journal in journals"
        :key="journal.id"
        :class="{ active: activeJournal === journal.id }"
        class="journal-btn"
        @click="activeJournal = journal.id as any"
      >
        <div class="icon">{{ journal.icon }}</div>
        <div class="info">
          <div class="name">{{ journal.name }}</div>
          <div class="description">{{ journal.description }}</div>
        </div>
      </button>
    </div>

    <div class="journal-content">
      <JournalViewer
        :key="activeJournal"
        :journal-type="activeJournal"
        :title="journals.find(j => j.id === activeJournal)?.name || 'Journal'"
      />
    </div>
  </div>
</template>

<style scoped>
.journals-page {
  max-width: 1400px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.page-header {
  margin-bottom: 3rem;
}

.page-header h1 {
  font-size: 2.5rem;
  color: #1f2937;
  margin: 0 0 0.5rem 0;
}

.page-header p {
  font-size: 1.1rem;
  color: #6b7280;
  margin: 0;
}

.journal-selector {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.journal-btn {
  background: white;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  padding: 1.5rem;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 1rem;
  text-align: left;
}

.journal-btn:hover {
  border-color: #3b82f6;
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
}

.journal-btn.active {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-color: transparent;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.icon {
  font-size: 2rem;
  line-height: 1;
}

.info {
  flex: 1;
}

.name {
  font-weight: 600;
  font-size: 1rem;
  margin-bottom: 0.25rem;
}

.description {
  font-size: 0.9rem;
  opacity: 0.7;
}

.journal-content {
  animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 768px) {
  .journal-selector {
    grid-template-columns: 1fr;
  }

  .journal-btn {
    flex-direction: column;
    text-align: center;
  }

  .page-header h1 {
    font-size: 2rem;
  }
}
</style>
