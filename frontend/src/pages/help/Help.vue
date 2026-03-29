<script setup lang="ts">
import { ref } from 'vue'

const activeTab = ref<'getting-started' | 'features' | 'faq' | 'shortcuts'>('getting-started')

const sections = {
  gettingStarted: [
    {
      title: 'Welcome to Tax Records',
      content:
        'Tax Records is a comprehensive application designed to help you manage your personal finances, tax records, and business assets in one place.',
    },
    {
      title: 'Getting Started',
      content:
        'After logging in, you will see the dashboard with shortcuts to different sections. Click on any section to view and manage your records.',
    },
    {
      title: 'First Steps',
      content:
        '1. Start with the "Income & Expense" section to record your financial transactions\n2. Add your assets in the "Assets" section for depreciation tracking\n3. Track demands and debts in "Demands & Debts"\n4. Manage your profile settings in "Profile"\n5. Customize your experience in "Settings"',
    },
  ],
  features: [
    {
      title: 'Income & Expense Management',
      content:
        'Track all your income and expenses. The system will help you categorize transactions by type (income/expense) and payment method. View trending analysis with an automatic linear regression chart to see if your income/expenses are trending up or down.',
      sections: ['Add Records', 'View Trends', 'Filter by Year', 'Export Data'],
    },
    {
      title: 'Asset Management',
      content:
        'Keep track of your fixed assets and their depreciation. Assign depreciation groups (1-6) based on asset type and expected lifespan. The system will automatically calculate depreciation.',
      sections: [
        'Register Assets',
        'Track Depreciation',
        'Mark as Disposed',
        'Calculate Tax Deductions',
      ],
    },
    {
      title: 'Demands & Debts Tracking',
      content:
        'Manage money you are owed (demands) and money you owe others (debts). Set due dates and track payment status. Mark records as paid when settled.',
      sections: ['Create Demands', 'Track Debts', 'Set Due Dates', 'Mark as Paid'],
    },
    {
      title: 'Dark Mode',
      content:
        'Switch to dark mode for comfortable viewing in low-light conditions. Your preference is automatically saved and restored on your next visit.',
    },
    {
      title: 'Data Export & Import',
      content:
        'Export your settings and preferences as JSON files for backup purposes. You can restore them anytime using the import function in Settings.',
    },
  ],
  faq: [
    {
      question: 'How is my data stored?',
      answer:
        'All your data is stored securely in our encrypted database. Your passwords are hashed and never stored in plain text. You maintain complete control over your data.',
    },
    {
      question: 'Can I access my records from multiple devices?',
      answer:
        'Yes! As long as you have internet access and your login credentials, you can access your records from any device.',
    },
    {
      question: 'How do I calculate depreciation for my assets?',
      answer:
        'The system uses standard depreciation groups (1-6) based on Czech tax law. Select the appropriate group for your asset, and the system will calculate depreciation automatically. You can also choose between linear and accelerated depreciation methods.',
    },
    {
      question: 'What if I need to edit or delete a record?',
      answer:
        'You can edit most records by clicking the edit button next to the record. Deleting records is also possible, though deleted records cannot be recovered, so use delete carefully.',
    },
    {
      question: 'How do I export my data?',
      answer:
        'Go to Settings > Data Management > Export Settings. This will download your preferences. For exporting actual financial records, you can use the table export features in each section.',
    },
    {
      question: 'Is there a mobile app?',
      answer: 'Tax Records is web-based and works on all devices with a modern web browser, including smartphones and tablets.',
    },
    {
      question: 'What happens if I forget my password?',
      answer:
        'Use the "Forgot Password" link on the login page to reset your password. You will receive a password reset link via email.',
    },
    {
      question: 'Can I import data from other applications?',
      answer:
        'Currently, you need to manually input data or contact support for bulk import assistance. We are working on support for importing from other accounting software.',
    },
  ],
  shortcuts: [
    { keys: 'Ctrl/Cmd + S', action: 'Save current form' },
    { keys: 'Ctrl/Cmd + D', action: 'Delete selected record' },
    { keys: 'Escape', action: 'Close dialog or cancel form' },
    { keys: 'Tab', action: 'Navigate between form fields' },
    { keys: 'Enter', action: 'Submit form' },
    { keys: 'Ctrl/Cmd + F', action: 'Focus search/filter field' },
    { keys: 'Click column header', action: 'Sort table by column' },
    { keys: 'Shift + Click', action: 'Multi-select table rows' },
  ],
}
</script>

<template>
  <div class="help-container">
    <div class="help-header">
      <h1>Help & Documentation</h1>
      <p>Learn how to use Tax Records and manage your personal finances effectively</p>
    </div>

    <div class="tabs">
      <button
        v-for="tab in ['getting-started', 'features', 'faq', 'shortcuts']"
        :key="tab"
        @click="activeTab = tab as any"
        :class="{ active: activeTab === tab }"
        class="tab-button"
      >
        {{ tab === 'getting-started' ? '🚀 Getting Started' : tab === 'features' ? '✨ Features' : tab === 'faq' ? '❓ FAQ' : '⌨️ Shortcuts' }}
      </button>
    </div>

    <div class="tab-content">
      <!-- Getting Started Tab -->
      <div v-show="activeTab === 'getting-started'" class="tab-pane">
        <div v-for="(section, index) in sections.gettingStarted" :key="index" class="content-section">
          <h2>{{ section.title }}</h2>
          <p>{{ section.content }}</p>
        </div>
      </div>

      <!-- Features Tab -->
      <div v-show="activeTab === 'features'" class="tab-pane">
        <div v-for="(feature, index) in sections.features" :key="index" class="content-section">
          <h2>{{ feature.title }}</h2>
          <p>{{ feature.content }}</p>
          <div v-if="feature.sections" class="feature-sections">
            <div v-for="(section, idx) in feature.sections" :key="idx" class="mini-section">
              • {{ section }}
            </div>
          </div>
        </div>
      </div>

      <!-- FAQ Tab -->
      <div v-show="activeTab === 'faq'" class="tab-pane">
        <div v-for="(item, index) in sections.faq" :key="index" class="faq-item">
          <h3>{{ item.question }}</h3>
          <p>{{ item.answer }}</p>
        </div>
      </div>

      <!-- Shortcuts Tab -->
      <div v-show="activeTab === 'shortcuts'" class="tab-pane">
        <h2>Keyboard Shortcuts</h2>
        <table class="shortcuts-table">
          <thead>
            <tr>
              <th>Shortcut</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in sections.shortcuts" :key="index">
              <td>
                <kbd>{{ item.keys }}</kbd>
              </td>
              <td>{{ item.action }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="help-footer">
      <div class="support-section">
        <h3>Need More Help?</h3>
        <p>
          If you can't find the answer you're looking for, please contact our support team at
          <a href="mailto:support@taxrecords.cz">support@taxrecords.cz</a>
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.help-container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.help-header {
  text-align: center;
  margin-bottom: 3rem;
}

.help-header h1 {
  font-size: 2.5rem;
  color: #1f2937;
  margin: 0 0 0.5rem 0;
}

.help-header p {
  font-size: 1.1rem;
  color: #6b7280;
  margin: 0;
}

.tabs {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 2rem;
  border-bottom: 2px solid #e5e7eb;
  flex-wrap: wrap;
}

.tab-button {
  flex: 1;
  min-width: 150px;
  padding: 1rem;
  border: none;
  background: none;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 500;
  color: #6b7280;
  border-bottom: 3px solid transparent;
  transition: all 0.2s;
  margin-bottom: -2px;
}

.tab-button:hover {
  color: #3b82f6;
}

.tab-button.active {
  color: #3b82f6;
  border-bottom-color: #3b82f6;
}

.tab-content {
  background: white;
  border-radius: 8px;
  padding: 2rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  min-height: 400px;
}

.tab-pane {
  animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.content-section {
  margin-bottom: 2rem;
}

.content-section h2 {
  font-size: 1.5rem;
  color: #1f2937;
  margin: 0 0 0.75rem 0;
}

.content-section p {
  color: #4b5563;
  line-height: 1.6;
  white-space: pre-wrap;
}

.feature-sections {
  margin-top: 1rem;
  padding-left: 1rem;
  border-left: 3px solid #e5e7eb;
}

.mini-section {
  padding: 0.5rem 0;
  color: #6b7280;
}

.faq-item {
  margin-bottom: 1.5rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.faq-item:last-child {
  border-bottom: none;
}

.faq-item h3 {
  color: #1f2937;
  margin: 0 0 0.5rem 0;
  font-size: 1.1rem;
}

.faq-item p {
  color: #4b5563;
  margin: 0;
  line-height: 1.6;
}

.shortcuts-table {
  width: 100%;
  border-collapse: collapse;
}

.shortcuts-table th,
.shortcuts-table td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid #e5e7eb;
}

.shortcuts-table th {
  background-color: #f3f4f6;
  font-weight: 600;
  color: #374151;
}

.shortcuts-table tr:hover {
  background-color: #f9fafb;
}

kbd {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  background-color: #f3f4f6;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  font-family: monospace;
  font-size: 0.9rem;
  color: #1f2937;
}

.help-footer {
  margin-top: 3rem;
  padding: 2rem;
  background-color: #f0f9ff;
  border: 1px solid #bfdbfe;
  border-radius: 8px;
}

.support-section h3 {
  color: #1f2937;
  margin-top: 0;
}

.support-section p {
  color: #4b5563;
  margin: 0;
}

.support-section a {
  color: #3b82f6;
  text-decoration: none;
}

.support-section a:hover {
  text-decoration: underline;
}
</style>
